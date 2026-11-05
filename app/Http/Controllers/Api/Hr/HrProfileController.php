<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreHrDocumentRequest;
use App\Http\Requests\Hr\StoreHrLeaveRequest;
use App\Http\Requests\Hr\StoreHrProfileRequest;
use App\Http\Requests\Hr\UpdateHrProfileRequest;
use App\Http\Requests\Hr\UpdateHrStatusRequest;
use App\Http\Resources\Hr\HrDocumentResource;
use App\Http\Resources\Hr\HrLeaveResource;
use App\Http\Resources\Hr\HrProfileResource;
use App\Models\HrDocument;
use App\Models\HrLeaveRecord;
use App\Models\HrProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrProfileController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('hr', 'view')) {
            abort(403);
        }

        $query = HrProfile::with(['user', 'department']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('position_title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($dq) use ($search) {
                        $dq->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        foreach (['department_id', 'employment_type', 'employment_status'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        return HrProfileResource::collection($query->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function store(StoreHrProfileRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $profile = HrProfile::create($data);

        return new HrProfileResource($profile->load(['user', 'department']));
    }

    public function show(Request $request, int $id)
    {
        if (!$request->user()->canAction('hr', 'view')) {
            abort(403);
        }

        $profile = HrProfile::with(['user', 'department', 'documents', 'leaveRecords.approver'])->findOrFail($id);

        return new HrProfileResource($profile);
    }

    public function update(UpdateHrProfileRequest $request, int $id)
    {
        $profile = HrProfile::findOrFail($id);
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $profile->update($data);

        return new HrProfileResource($profile->fresh(['user', 'department']));
    }

    public function status(UpdateHrStatusRequest $request, int $id)
    {
        $profile = HrProfile::findOrFail($id);

        $profile->update([
            'employment_status' => $request->input('employment_status'),
            'notes' => $request->input('notes', $profile->notes),
            'updated_by' => $request->user()->id,
        ]);

        return new HrProfileResource($profile->fresh(['user', 'department']));
    }

    public function documents(Request $request, int $id)
    {
        if (!$request->user()->canAction('hr', 'view')) {
            abort(403);
        }

        $profile = HrProfile::findOrFail($id);

        return HrDocumentResource::collection($profile->documents()->orderByDesc('id')->get());
    }

    public function storeDocument(StoreHrDocumentRequest $request, int $id)
    {
        $profile = HrProfile::findOrFail($id);
        $file = $request->file('file');

        $path = $file->store('hr_documents', 'local');

        $document = HrDocument::create([
            'hr_profile_id' => $profile->id,
            'document_type' => $request->input('document_type'),
            'title' => $request->input('title'),
            'file_path' => $path,
            'original_file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return new HrDocumentResource($document);
    }

    public function downloadDocument(Request $request, int $profileId, int $documentId)
    {
        if (!$request->user()->canAction('hr', 'documents.manage')) {
            abort(403);
        }

        $document = HrDocument::where('hr_profile_id', $profileId)->findOrFail($documentId);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($document->file_path, $document->original_file_name);
    }

    public function leave(Request $request, int $id)
    {
        if (!$request->user()->canAction('hr', 'view')) {
            abort(403);
        }

        $profile = HrProfile::findOrFail($id);

        return HrLeaveResource::collection($profile->leaveRecords()->orderByDesc('id')->get());
    }

    public function storeLeave(StoreHrLeaveRequest $request, int $id)
    {
        $profile = HrProfile::findOrFail($id);

        $leave = HrLeaveRecord::create([
            'hr_profile_id' => $profile->id,
            'leave_type' => $request->input('leave_type'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'days' => $request->input('days'),
            'status' => 'pending',
            'reason' => $request->input('reason'),
        ]);

        return new HrLeaveResource($leave);
    }

    public function approveLeave(Request $request, int $id)
    {
        if (!$request->user()->canAction('hr', 'leave.manage')) {
            abort(403);
        }

        $leave = HrLeaveRecord::findOrFail($id);

        $leave->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return new HrLeaveResource($leave->fresh(['approver']));
    }

    public function stats(Request $request)
    {
        if (!$request->user()->canAction('hr', 'view')) {
            abort(403);
        }

        $now = now();
        $profiles = HrProfile::with('user', 'department')->get();

        $byType = [];
        foreach ($profiles as $p) {
            $byType[$p->employment_type] = ($byType[$p->employment_type] ?? 0) + 1;
        }

        $expiring = [
            '30' => 0,
            '60' => 0,
            '90' => 0,
        ];
        foreach ($profiles as $p) {
            if ($p->contract_end_date) {
                $days = $now->diffInDays($p->contract_end_date, false);
                if ($days >= 0 && $days <= 30) $expiring['30']++;
                if ($days >= 0 && $days <= 60) $expiring['60']++;
                if ($days >= 0 && $days <= 90) $expiring['90']++;
            }
        }

        return response()->json([
            'total_active' => $profiles->where('employment_status', 'active')->count(),
            'by_employment_type' => $byType,
            'contracts_expiring' => $expiring,
            'pending_leave' => HrLeaveRecord::where('status', 'pending')->count(),
            'on_leave' => $profiles->where('employment_status', 'on_leave')->count(),
            'recent_updates' => $profiles->sortByDesc('updated_at')->take(5)->values(),
        ]);
    }

    public function export(Request $request)
    {
        if (!$request->user()->canAction('hr', 'export')) {
            abort(403);
        }

        $profiles = HrProfile::with(['user', 'department'])->get();

        $headers = ['Employee Number', 'Name', 'Department', 'Position', 'Employment Type', 'Status', 'Contract End'];

        $callback = function () use ($profiles, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($profiles as $p) {
                fputcsv($out, [
                    $p->employee_number,
                    $p->user?->first_name . ' ' . $p->user?->last_name,
                    $p->department?->name,
                    $p->position_title,
                    $p->employment_type,
                    $p->employment_status,
                    $p->contract_end_date,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'hr-profiles.csv', ['Content-Type' => 'text/csv']);
    }

    public function rejectLeave(Request $request, int $id)
    {
        if (!$request->user()->canAction('hr', 'leave.manage')) {
            abort(403);
        }

        $leave = HrLeaveRecord::findOrFail($id);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return new HrLeaveResource($leave->fresh(['approver']));
    }
}
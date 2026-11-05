<?php

namespace App\Http\Controllers\Api\Proposal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proposal\StoreProposalActivityRequest;
use App\Http\Requests\Proposal\StoreProposalAttachmentRequest;
use App\Http\Requests\Proposal\StoreProposalKpiRequest;
use App\Http\Requests\Proposal\StoreProposalRequest;
use App\Http\Requests\Proposal\UpdateProposalActivityRequest;
use App\Http\Requests\Proposal\UpdateProposalKpiRequest;
use App\Http\Requests\Proposal\UpdateProposalRequest;
use App\Http\Resources\Proposal\ProposalActivityResource;
use App\Http\Resources\Proposal\ProposalAttachmentResource;
use App\Http\Resources\Proposal\ProposalKpiResource;
use App\Http\Resources\Proposal\ProposalResource;
use App\Models\Department;
use App\Models\Proposal;
use App\Models\ProposalActivity;
use App\Models\ProposalAttachment;
use App\Models\ProposalKpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('proposals', 'view')) {
            abort(403);
        }

        $query = Proposal::with(['department', 'owner']);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        return ProposalResource::collection($query->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function store(StoreProposalRequest $request)
    {
        if (!$request->user()->canAction('proposals', 'create')) {
            abort(403);
        }

        $year = now()->year;

        $proposal = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $year) {
            $count = Proposal::withTrashed()->whereYear('created_at', $year)->lockForUpdate()->count() + 1;
            $reference = 'PROP-' . $year . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);

            return Proposal::create(array_merge($request->validated(), [
                'reference_number' => $reference,
                'owner_id' => $request->user()->id,
                'status' => 'draft',
            ]));
        });

        return new ProposalResource($proposal->load(['department', 'owner']));
    }

    public function show(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'view')) {
            abort(403);
        }

        $proposal = Proposal::with(['department', 'owner', 'activities.responsiblePerson', 'kpis', 'attachments'])->findOrFail($id);

        return new ProposalResource($proposal);
    }

    public function update(UpdateProposalRequest $request, int $id)
    {
        $proposal = Proposal::findOrFail($id);

        if (in_array($proposal->status, ['published', 'closed'])) {
            return response()->json(['message' => 'Published or closed proposals are read-only.'], 422);
        }

        $proposal->update($request->validated());

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function destroy(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'update')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);
        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted.']);
    }

    public function submit(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'submit')) {
            abort(403);
        }

        $proposal = Proposal::with('activities')->findOrFail($id);

        if ($proposal->activities->isEmpty()) {
            return response()->json(['message' => 'Proposal cannot be submitted without at least one activity.'], 422);
        }

        $proposal->update([
            'status' => 'submitted',
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function review(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'review')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        $proposal->update([
            'status' => 'under_review',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function approve(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'approve')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        if ((float) $proposal->total_budget_amount <= 0) {
            return response()->json(['message' => 'Proposal cannot be approved without a total budget amount.'], 422);
        }

        $proposal->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function reject(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'reject')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        $proposal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason'),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function publish(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'publish')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        if ($proposal->status !== 'approved') {
            return response()->json(['message' => 'Only approved proposals can be published.'], 422);
        }

        $proposal->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function close(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'update')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        $proposal->update(['status' => 'closed']);

        return new ProposalResource($proposal->fresh(['department', 'owner', 'activities', 'kpis', 'attachments']));
    }

    public function storeActivity(StoreProposalActivityRequest $request, int $id)
    {
        $proposal = Proposal::findOrFail($id);

        $activity = ProposalActivity::create(array_merge($request->validated(), [
            'proposal_id' => $proposal->id,
        ]));

        return new ProposalActivityResource($activity->load('responsiblePerson'));
    }

    public function updateActivity(UpdateProposalActivityRequest $request, int $id)
    {
        $activity = ProposalActivity::findOrFail($id);
        $activity->update($request->validated());

        return new ProposalActivityResource($activity->fresh('responsiblePerson'));
    }

    public function storeKpi(StoreProposalKpiRequest $request, int $id)
    {
        $proposal = Proposal::findOrFail($id);

        $kpi = ProposalKpi::create(array_merge($request->validated(), [
            'proposal_id' => $proposal->id,
        ]));

        return new ProposalKpiResource($kpi);
    }

    public function updateKpi(UpdateProposalKpiRequest $request, int $id)
    {
        $kpi = ProposalKpi::findOrFail($id);
        $kpi->update($request->validated());

        return new ProposalKpiResource($kpi->fresh());
    }

    public function attachments(Request $request, int $id)
    {
        if (!$request->user()->canAction('proposals', 'view')) {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);

        return ProposalAttachmentResource::collection($proposal->attachments()->orderByDesc('id')->get());
    }

    public function storeAttachment(StoreProposalAttachmentRequest $request, int $id)
    {
        $proposal = Proposal::findOrFail($id);
        $file = $request->file('file');

        $path = $file->store('proposal_attachments', 'local');

        $attachment = ProposalAttachment::create([
            'proposal_id' => $proposal->id,
            'title' => $request->input('title'),
            'file_path' => $path,
            'original_file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return new ProposalAttachmentResource($attachment);
    }

    public function export(Request $request)
    {
        if (!$request->user()->canAction('proposals', 'export')) {
            abort(403);
        }

        $proposals = Proposal::with(['department', 'owner'])->get();

        $headers = ['Reference', 'Title', 'Type', 'Department', 'Status', 'Budget', 'Progress'];

        $callback = function () use ($proposals, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($proposals as $p) {
                $activities = $p->activities ?? collect();
                $progress = $activities->count() > 0 ? round($activities->avg('progress_percentage'), 2) : 0;
                fputcsv($out, [
                    $p->reference_number,
                    $p->title,
                    $p->type,
                    $p->department?->name,
                    $p->status,
                    $p->total_budget_amount,
                    $progress,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'proposals.csv', ['Content-Type' => 'text/csv']);
    }

    public function downloadAttachment(Request $request, int $proposalId, int $attachmentId)
    {
        if (!$request->user()->canAction('proposals', 'view')) {
            abort(403);
        }

        $attachment = ProposalAttachment::where('proposal_id', $proposalId)->findOrFail($attachmentId);

        if (!Storage::disk('local')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($attachment->file_path, $attachment->original_file_name);
    }
}
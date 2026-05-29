<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with('program');
        
        // If not explicitely requested to see all, only show active
        if (!$request->has('all')) {
            $query->where('is_active', true);
        }
        
        if ($request->has('program_id')) {
            $query->where(function($q) use ($request) {
                $q->where('program_id', $request->program_id)
                  ->orWhere('type', 'direct');
            });
        }
        
        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:tuition,direct',
            'program_id' => 'nullable|exists:programs,id',
            'applicable_semester' => 'required|in:semester_1,semester_2,both',
            'semester_1_amount' => 'required|numeric|min:0',
            'semester_2_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $total = $request->semester_1_amount + $request->semester_2_amount;
        $data = $request->all();
        $data['total_amount'] = $total;

        $fee = Fee::create($data);
        return response()->json($fee->load('program'), 201);
    }

    public function update(Request $request, $id)
    {
        $fee = Fee::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:tuition,direct',
            'program_id' => 'nullable|exists:programs,id',
            'applicable_semester' => 'required|in:semester_1,semester_2,both',
            'semester_1_amount' => 'required|numeric|min:0',
            'semester_2_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $total = $request->semester_1_amount + $request->semester_2_amount;
        $data = $request->all();
        $data['total_amount'] = $total;

        $fee->update($data);
        return response()->json($fee->load('program'));
    }

    public function destroy($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();
        return response()->json(['message' => 'Fee deleted successfully']);
    }
}

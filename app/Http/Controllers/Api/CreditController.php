<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreditController extends Controller
{
    public function index()
    {
        $credits = Credit::all();
        return response()->json($credits);
    }

    public function show($id)
    {
        $credit = Credit::findOrFail($id);
        return response()->json($credit);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:credits',
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $credit = Credit::create([
            'code' => $request->code,
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($credit, 201);
    }

    public function update(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:credits,code,' . $id,
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $credit->update([
            'code' => $request->code,
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'is_active' => $request->is_active ?? $credit->is_active,
        ]);

        return response()->json($credit);
    }

    public function destroy($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->delete();
        return response()->json(['message' => 'Credit deleted successfully']);
    }
}

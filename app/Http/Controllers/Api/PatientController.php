<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Patient::all();
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255',

            'occupation' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|max:255',
            'parent_occupation' => 'required|max:255',
            'parent_address' => 'required|max:255',
        ]);

        $patient = Patient::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
        ]);

        $patient->detail()->create([
            'occupation' => $data['occupation'],
            'date_of_birth' => $data['date_of_birth'],
            'parent_name' => $data['parent_name'],
            'parent_occupation' => $data['parent_occupation'],
            'parent_address' => $data['parent_address']
        ]);
        return response()->json($patient->load('detail'), 201);
    }

    public function show(string $id)
    {
        $data = Patient::with('detail')->findOrFail($id);
        return response()->json($data, 200);
    }

    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255',

            'occupation' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|max:255',
            'parent_occupation' => 'required|max:255',
            'parent_address' => 'required|max:255'
        ]);

        $patient->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
        ]);

        $patient->detail()->update([
            'occupation' => $data['occupation'],
            'date_of_birth' => $data['date_of_birth'],
            'parent_name' => $data['parent_name'],
            'parent_occupation' => $data['parent_occupation'],
            'parent_address' => $data['parent_address']
        ]);
        return response()->json($patient->load('detail'), 200);
    }


    public function destroy(string $id)
    {
        $data = Patient::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }
}

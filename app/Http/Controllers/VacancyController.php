<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Log;

class VacancyController extends Controller
{
    // Retrieve all vacancies
    public function getAll()
    {
        $vacancies = Vacancy::orderBy('created_at', 'desc')->get();
        return response()->json($vacancies);
    }

    // Retrieve a single vacancy
    public function getById($id)
    {
        $vacancy = Vacancy::find($id);
        if (!$vacancy) {
            return response()->json([
                'message' => 'Vacancy not found!'
            ], 404);
        }
        return response()->json($vacancy);
    }

    // Create a new vacancy
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'salary' => 'nullable|integer',
            'location' => 'nullable|string',
            'company' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $vacancy = new Vacancy();
        $vacancy->title = $validatedData['title'];
        $vacancy->salary = $validatedData['salary'];
        $vacancy->location = $validatedData['location'];
        $vacancy->company = $validatedData['company'];
        $vacancy->description = $validatedData['description'];

        if ($vacancy->save()) {
            return response()->json($vacancy, 201);
        } else {
            return response()->json([
                'message' => 'Error while creating vacancy'
            ], 500);
        }
    }
    // Update a vacancy
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'salary' => 'nullable|integer',
            'location' => 'nullable|string',
            'company' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $vacancy = Vacancy::find($id);
        if (!$vacancy) {
            return response()->json([
                'message' => 'Vacancy not found!'
            ], 404);
        }

        $vacancy->title = $validatedData['title'];
        $vacancy->salary = $validatedData['salary'];
        $vacancy->location = $validatedData['location'];
        $vacancy->company = $validatedData['company'];
        $vacancy->description = $validatedData['description'];

        if ($vacancy->save()) {
            return response()->json($vacancy, 200);
        } else {
            return response()->json([
                'message' => 'Error while updating vacancy'
            ], 500);
        }
    }

    // Delete a vacancy
    public function delete($id)
    {
        $vacancy = Vacancy::find($id);
        if (!$vacancy) {
            return response()->json([
                'message' => 'Vacancy not found!'
            ], 404);
        }
        if ($vacancy->delete()) {
            return response()->json([
                'message' => 'Vacancy deleted successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error while deleting vacancy'
            ], 500);
        }
    }
}

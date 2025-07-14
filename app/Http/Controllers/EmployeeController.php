<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $filter = $request->validate([
            'name' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $limit = $request->input('limit', 10);

        $data = Employee::with('division')
            ->select(['id', 'image', 'name', 'phone', 'division_id', 'position'])
            ->when($filter['name'] ?? null, function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($filter['division_id'] ?? null, function ($query, $divisionId) {
                return $query->where('division_id', $divisionId);
            })->paginate($limit);

        return $this->success(
            message: 'Employees retrieved successfully',
            data: $data->items(),
            pagination: $data,
        );
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'image' => 'required|image|max:2048',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|exists:divisions,id',
            'position' => 'required|string|max:255',
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employees', 'public');
            $payload['image'] = $imagePath;
        }

        Employee::create([
            'image' => $payload['image'],
            'name' => $payload['name'],
            'phone' => $payload['phone'],
            'division_id' => $payload['division_id'],
            'position' => $payload['position'],
        ]);

        return $this->success(
            message: 'Employee created successfully',
        );
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $payload = $request->validate([
            'image' => 'sometimes|image|max:2048',
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'division_id' => 'sometimes|exists:divisions,id',
            'position' => 'sometimes|string|max:255',
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employees', 'public');
            $payload['image'] = $imagePath;
        }

        $employee->update($payload);

        return $this->success(
            message: 'Employee updated successfully',
        );
    }
}

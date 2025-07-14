<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class DivisionController extends Controller
{
    use ApiResponse;
    public function index(Request $request): JsonResponse
    {
        $filter = $request->validate([
            'name' => 'nullable|string',
        ]);

        $limit = $request->input('limit', 10);

        $divisions = Division::where('name', 'like', '%' . ($filter['name'] ?? '') . '%')->paginate($limit);

        $paginationData = [
        ];

        Log::channel('stderr')->info('Divisions retrieved successfully', [
            'filter' => $filter,
            'pagination' => $paginationData,
        ]);

        return $this->success(
            message: 'Divisions retrieved successfully',
            data: $divisions,
        );
    }
}

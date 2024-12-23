<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use App\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $incomes = Income::where('user_id', $userId)->with('incomeSource')->paginate(10);

        return ApiResponseService::success($incomes, 'Incomes retrieved successfully', Response::HTTP_OK);
    }


    public function store(IncomeRequest $request): JsonResponse
    {
        $userId = Auth::id();

        $data = $request->validated();
        $data['user_id'] = $userId;

        $income = Income::create($data);

        return ApiResponseService::success($income, 'Income created successfully', Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $income = Income::findOrFail($id);
            $this->authorize('view', $income);

            return ApiResponseService::success($income, 'Income retrieved successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResponseService::error('Income not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(IncomeRequest $request, int $id): JsonResponse
    {
        try {
            $income = Income::findOrFail($id);
            $this->authorize('update', $income);

            $validatedData = $request->validated();
            $income->update($validatedData);

            return ApiResponseService::success($income, 'Income updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResponseService::error('Income not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $income = Income::findOrFail($id);
            $this->authorize('update', $income);

            return ApiResponseService::success($income, 'Income retrieved successfully for editing', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResponseService::error('Income not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $income = Income::findOrFail($id);
            $this->authorize('delete', $income);

            $income->delete();

            return ApiResponseService::success(null, 'Income deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResponseService::error('Income not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function searchIncome(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $query = Income::where('user_id', $userId);
        $query = Income::query();

        if ($request->filled('amount')) {
            $query->where('amount', '=', $request->amount);
        }

        if ($request->filled('date_received')) {
            $query->where('date_received', '=', $request->date_received);
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        $incomes = $query->get();

        return ApiResponseService::success($incomes, 'Incomes retrieved successfully', Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Expense_category;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ExpensesController extends Controller
{
    
    public function index()
    {
        try{
            $query = Expense::query()->with(['expense_categories', 'budgets']);
    
            if(request()->has('search')){
                $query->where('date_spend', 'LIKE', '%'.request('search').'%')
                ->orWhere('description', 'LIKE', '%'.request('search').'%');
            }
    
            $expenses= $query->where('user_id', Auth::id() ? Auth::id() : throw new Exception('user is not authentic'))->latest()->paginate(10);

        } catch (Exception $e){
            return ApiResponseService::error( 'error occurs during retrival', $e->getMessage(), 400);
        }

        return ApiResponseService::success($expenses, 'expenses retrived successfully', 200);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount'=> 'required|numeric',
                'date_spend'=> 'required|date',
                'description'=> 'required|string',
                'user_id'=> 'required|numeric',
                'category_id'=> 'required|numeric',
                'budget_id'=> 'required|numeric'
            ]);

            Expense::create($request->all());

        } catch (Exception $e){
            return ApiResponseService::error('error', $e->getMessage());
        }

        return  ApiResponseService::success(null,'Expense added successfully', 201);
    }

    
    public function show(Expense $expense)
    {
        $expense->load(['expense_categories', 'budgets']);
        return ApiResponseService::success($expense, 'expense details retrived', 200);
    }


    public function edit(Expense $expense)
    {
        $categories= Expense_category::all();
        $budgets= Budget::all();

        $responseData = [
            'expense' => $expense,
            'categories' => $categories,
            'budgets' => $budgets,
        ];

        return ApiResponseService::success($responseData, 'edit your data', 200);
    }

    
    public function update(Request $request, Expense $expense)
    {
        try{
            $request->validate([
                'amount'=> 'required|numeric',
                'date_spend'=> 'required|date',
                'description'=> 'required|string',
                'user_id'=> 'required|numeric',
                'category_id'=> 'required|numeric',
                'budget_id'=> 'required|numeric'
            ]);

            $expense->update($request->all());
        } catch(Exception $e){
            return ApiResponseService::error('error', $e->getMessage());
        }

        
        return ApiResponseService::success(null, 'data updated successfully', 200);
    }


    public function destroy(Expense $expense)
    {
        $expense->delete();
        return ApiResponseService::success(null, 'Record deleted successfully', 200);
    }
}

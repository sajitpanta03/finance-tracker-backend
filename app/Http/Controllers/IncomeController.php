<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        // $user = auth()->user();

        // $query = Income::find()->all();
    }
}

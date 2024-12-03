<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequist;
use App\Models\User;
use App\Trait\SanctumToken;
use Illuminate\Http\Request;

class loginController extends Controller
{
    use SanctumToken;

    public function index():void{

    }
    public function login(LoginRequist $request){

        $user =  $request->authenticate();

        return $this->GenerateToken($user);

    }
}

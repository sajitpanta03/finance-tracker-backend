<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequist;
use App\Models\User;
use App\Trait\SanctumToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    use SanctumToken;

    public function index():void{

    }
    public function login(LoginRequist $request){

        $user =  $request->authenticate();
        return [ "token" => $this->GenerateToken($user ,$request->bollen('remember'))];

    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}

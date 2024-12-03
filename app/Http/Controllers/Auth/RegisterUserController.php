<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Trait\SanctumToken;

class RegisterUserController extends Controller
{
    use SanctumToken;
    public function store(Request $request)
    {

        $request->validate([
            "name" => "required",
            "email" => "required|unique:" . User::class,
            "password" => "required|min:8|max:16"
        ]);
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $this->GenerateToken($user);


            DB::commit();
            
            return response()->json([
                "token"=> $token
            ]);
            

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'An error occurred during the operation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

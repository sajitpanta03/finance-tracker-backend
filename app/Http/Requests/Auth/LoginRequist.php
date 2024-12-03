<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class LoginRequist extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "password" => "required",
        ];
    }

    public function authenticate()
    {
        // dd($this->only('email', 'password'));

        $this->IsNotRateLimited();

        $credentials = $this->only('email', 'password');

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($this->throtalkey());
            return Auth::user();
        }

        RateLimiter::hit($this->throtalkey() );

        throw ValidationException::withMessages([
            'password' => trans('auth.failed'),
        ]);
    }

    public function IsNotRateLimited(){
        if (!RateLimiter::tooManyAttempts($this->throtalkey(), 5)){
            return;
        }
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throtalkey());
 
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throtalkey());
 
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    public function throtalkey(){
        return Str::transliterate(Str::lower($this->string('email') .'|'. $this->ip));
    }
}

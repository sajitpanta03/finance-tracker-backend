<?php
namespace App\Trait;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponseTrait
{
    public function failedValidator(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'validation failed',
            'errors' => $validator->errors(),
        ]));
    }
}
?>
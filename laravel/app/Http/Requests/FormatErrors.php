<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait FormatErrors
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $formatedErrors = [];

        foreach($errors as $key =>  $error){
            $formatedErrors[$key] = $error[0] ?? $error;
        }

        throw new HttpResponseException(
            response()->json(['errors' => $formatedErrors], Response::HTTP_BAD_REQUEST)
        );
    }
}

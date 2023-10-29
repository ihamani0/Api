<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Middleware\TrustProxies;

class RegisterRequest extends FormRequest
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
            'name' => ['required'] ,
            'email' => ['required' , 'email' , 'string'] ,
            'password' => ['required' , 'min:4' , 'max:12'] ,
            'mobile' => ['required' , 'numeric'] ,
        ];
    }
}

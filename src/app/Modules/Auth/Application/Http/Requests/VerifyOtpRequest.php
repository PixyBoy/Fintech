<?php

namespace App\Modules\Auth\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    protected $errorBag = 'verifyOtp';
    public function rules(): array
    {
        return [
            'phone' => ['required','regex:/^(?:\+98|0)?9\d{9}$/'],
            'code'  => ['required','digits_between:4,6'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

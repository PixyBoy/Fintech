<?php

namespace App\Modules\Auth\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestOtpRequest extends FormRequest
{
    protected $errorBag = 'requestOtp';
    public function rules(): array
    {
        return ['phone' => ['required','regex:/^(?:\+98|0)?9\d{9}$/']];
    }

    public function authorize(): bool
    {
        return true;
    }
}

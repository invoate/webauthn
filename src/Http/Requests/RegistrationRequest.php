<?php

namespace Invoate\WebAuthn\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user() instanceof WebAuthnticatable) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}

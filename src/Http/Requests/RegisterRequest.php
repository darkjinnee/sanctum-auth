<?php

namespace Darkjinnee\SanctumAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'data.*' => ['required', 'array'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'data.password' => ['required', 'string', 'min:8', 'confirmed'],
            'data.password_confirmation' => ['required', 'string', 'min:8'],
            'data.token_name' => ['string', 'max:255'],
        ];
    }
}

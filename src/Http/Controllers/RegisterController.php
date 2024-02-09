<?php

namespace Darkjinnee\SanctumAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Class RegisterController
 */
class RegisterController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $request = app($this->classes['register_request']);
        $data = $request->input('data');

        $data[$this->fields['password']] = Hash::make($data[$this->fields['password']]);
        $user = $this->classes['user_model']::create($data);
        $data = $this->getTokensPlainText($user, $data['token_name'] ?? null);

        return response()->json([
            'message' => 'User registered successfully.',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}

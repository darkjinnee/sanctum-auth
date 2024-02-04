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
        $request = app($this->classes['register']);
        $input = $request->all();

        $input[$this->fields['password']] = Hash::make($input[$this->fields['password']]);
        $user = $this->classes['user']::create($input);
        $data = $this->getTokensPlainText($user, $input['token_name'] ?? null);

        return response()->json([
            'message' => 'User registered successfully.',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}

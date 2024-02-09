<?php

namespace Darkjinnee\SanctumAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Class TokenController
 */
class TokenController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $request = app($this->classes['token_request']);
        $data = $request->input('data');

        $user = $this->classes['user_model']::where($this->fields['username'],
            $data[$this->fields['username']])->first();

        if (! $user || ! Hash::check($data[$this->fields['password']], $user[$this->fields['password']])) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'The provided credentials are incorrect.',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = $this->getTokensPlainText($user, $data['token_name'] ?? null);

        return response()->json([
            'message' => 'Token created successfully.',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}

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
        $request = app($this->classes['token']);
        $input = $request->all();

        $user = $this->classes['user']::where($this->fields['username'],
            $input[$this->fields['username']])->first();

        if (! $user || ! Hash::check($input[$this->fields['password']], $user[$this->fields['password']])) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'The provided credentials are incorrect.',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = $this->getTokensPlainText($user, $input['token_name'] ?? null);

        return response()->json([
            'message' => 'Token created successfully.',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}

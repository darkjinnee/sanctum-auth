<?php

namespace Darkjinnee\SanctumAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class RefreshController
 */
class RefreshController extends BaseController
{
    /**
     * @return JsonResponse|Response
     */
    public function __invoke(): JsonResponse|Response
    {
        $user = auth()->user();
        $refreshToken = $user->currentAccessToken();

        /** @phpstan-ignore-next-line */
        if ($parentToken = $refreshToken->parent) {
            $parentToken->update(['expires_at' => $this->expiresAt($this->expiration)]);

            return response()->noContent();
        }

        return response()->json([
            'message' => 'Incorrect token.',
        ], Response::HTTP_BAD_REQUEST);
    }
}

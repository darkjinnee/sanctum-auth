<?php

namespace Darkjinnee\SanctumAuth\Http\Middleware;

use Closure;
use Darkjinnee\SanctumAuth\Models\Client;
use Darkjinnee\SanctumAuth\SanctumAuth;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Class VerifySanctumAuth
 */
class VerifySanctumAuth
{
    /**
     * @var Repository|Application|mixed
     */
    protected mixed $verify;

    /**
     * @var array<string>
     */
    protected array $agent;

    /**
     * WicketControl constructor.
     */
    public function __construct()
    {
        $this->verify = config('sanctum-auth.verify');
        $this->agent = SanctumAuth::getClient();
    }

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $this->verify['enable'] || ! auth()->check()) {
            return $next($request);
        }

        if (! $user = auth()->user()) {
            return $next($request);
        }

        /** @phpstan-ignore-next-line */
        $tokenAbilities = $user->currentAccessToken()->abilities;
        $routeName = $request->route()->getName();

        foreach ($tokenAbilities as $ability) {
            if (Str::is($ability, $routeName)) {
                if ($client = $user->currentClient()) {
                    return $this->verityClient($client,
                        static fn (): mixed => $next($request));
                }

                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Access denied.',
            'errors' => [
                'access' => 'User has insufficient ability.',
            ],
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * @param  Client  $currentClient
     * @param  callable  $callback
     * @return mixed
     */
    public function verityClient(Client $currentClient, callable $callback): mixed
    {
        $verify = [
            'user_agent' => $currentClient->user_agent === $this->agent['user_agent'],
            'ip_address' => $currentClient->ip_address === $this->agent['ip_address'],
        ];

        switch ($this->verify['mode']) {
            case 1:
                if (! $verify['user_agent']) {
                    return response()->json([
                        'message' => 'Access denied.',
                        'errors' => [
                            'access' => 'Invalid user-agent.',
                        ],
                    ], Response::HTTP_FORBIDDEN);
                }
                break;
            case 2:
                if (! $verify['ip_address']) {
                    return response()->json([
                        'message' => 'Access denied.',
                        'errors' => [
                            'access' => 'Invalid ip address.',
                        ],
                    ], Response::HTTP_FORBIDDEN);
                }
                break;
            case 3:
                if (! $verify['user_agent'] || ! $verify['ip_address']) {
                    return response()->json([
                        'message' => 'Access denied.',
                        'errors' => [
                            'access' => 'Invalid user-agent or ip address.',
                        ],
                    ], Response::HTTP_FORBIDDEN);
                }
                break;
            default:
                break;
        }

        return $callback();
    }
}

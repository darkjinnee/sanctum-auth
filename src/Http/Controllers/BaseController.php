<?php

namespace Darkjinnee\SanctumAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Darkjinnee\SanctumAuth\Http\Requests\RegisterRequest;
use Darkjinnee\SanctumAuth\Http\Requests\TokenRequest;
use Darkjinnee\SanctumAuth\SanctumAuth;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Carbon;

/**
 * Class BaseController
 */
class BaseController extends Controller
{
    /**
     * @var Repository|Application|mixed
     */
    protected mixed $fields;

    /**
     * @var Repository|Application|mixed
     */
    protected mixed $classes;

    /**
     * @var array<string, mixed>
     */
    protected array $refreshToken;

    /**
     * @var array<string>
     */
    protected array $client;

    /**
     * @var int|null
     */
    protected ?int $expiration;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->fields = config('sanctum-auth.fields', [
            'username' => 'email',
            'password' => 'password',
        ]);
        $this->classes = config('sanctum-auth.classes', [
            'user' => User::class,
            'token' => TokenRequest::class,
            'register' => RegisterRequest::class,
        ]);
        $this->refreshToken = config('sanctum-auth.refresh_token');
        $this->expiration = config('sanctum.expiration');
        $this->client = SanctumAuth::getClient();
    }

    /**
     * @param  mixed  $user
     * @param  string|null  $tokenName
     * @return array<string, string>
     */
    public function getTokensPlainText(mixed $user, string $tokenName = null): array
    {
        $name = $tokenName ?? $this->client['user_agent'];
        $parentToken = $user->createToken($name,
            $user->masks(),
            $this->expiresAt($this->expiration));

        if ($this->refreshToken['enable']) {
            $childToken = $parentToken->accessToken->createChild($name,
                $this->refreshToken['masks'],
                $this->expiresAt($this->refreshToken['expiration']));
        }
        $user->createClient($parentToken->accessToken->id, $this->client);

        return [
            'access_token' => $parentToken->plainTextToken,
            'refresh_token' => $childToken->plainTextToken ?? null,
        ];
    }

    /**
     * @param  ?int  $expiration
     * @return Carbon|null
     */
    public function expiresAt(?int $expiration): ?Carbon
    {
        return $expiration
            ? Carbon::now()->addMinutes($this->expiration)
            : null;
    }
}

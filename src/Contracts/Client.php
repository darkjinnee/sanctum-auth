<?php

namespace Darkjinnee\SanctumAuth\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Interface Client
 */
interface Client
{
    /**
     * @param  string  $userAgent
     * @return Collection
     */
    public static function findByUserAgent(string $userAgent): Collection;

    /**
     * @param  string  $ipAddress
     * @return Collection
     */
    public static function findByIpAddress(string $ipAddress): Collection;

    /**
     * @param  string  $id
     * @return Model|null
     */
    public static function findById(string $id): ?Model;

    /**
     * @param  int  $id
     * @return Model|null
     */
    public static function findByTokenId(int $id): ?Model;

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo;

    /**
     * @return MorphTo
     */
    public function user(): MorphTo;
}

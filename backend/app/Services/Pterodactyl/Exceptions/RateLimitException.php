<?php

/*
 * This file is part of MythicalDash.
 * Please view the LICENSE file that was distributed with this source code.
 *
 * # MythicalSystems License v2.0
 *
 * ## Copyright (c) 2021–2025 MythicalSystems and Cassian Gherman
 *
 * Breaking any of the following rules will result in a permanent ban from the MythicalSystems community and all of its services.
 */

namespace MythicalDash\Services\Pterodactyl\Exceptions;

class RateLimitException extends PterodactylException
{
    protected int $retryAfter;

    public static function withRetryAfter(int $seconds): self
    {
        $exception = new self('Too many requests. Please try again later.');
        $exception->retryAfter = $seconds;

        return $exception;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}

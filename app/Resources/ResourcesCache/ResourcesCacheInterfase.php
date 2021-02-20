<?php

declare(strict_types = 1);

namespace App\Resources\ResourcesCache;

interface ResourcesCacheInterface extends SimpleCacheInterface
{
    /**
     * Setter
     * 
     * @param string $key,
     * @param array $value,
     * @param int $cacheTime Cache time in minutes
     * 
     * @return array
     */
    public function setex(string $key, array $value, int $cacheTime): array;
}
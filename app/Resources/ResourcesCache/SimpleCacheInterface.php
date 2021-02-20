<?php

declare(strict_types = 1);

namespace App\Resources\ResourcesCache;

interface SimpleCacheInterface
{
    /**
     * Getter
     * 
     * @param string $key
     * 
     * @return array|null
     */
    public function get(string $key): ?array;

    /**
     * Setter
     * 
     * @var string $key
     * @var array $value
     * 
     * @return array
     */
    public function set(string $key, array $value): array; 
}
<?php

declare(strict_types = 1);

namespace App\Resources\ResourcesCache;

class SimpleCache implements SimpleCacheInterface
{
    /**
     * Cached values
     * 
     * @var array
     */
    protected $cached = [];

    /**
     * Getter
     * 
     * @param string $key
     * 
     * @return array|null
     */
    public function get(string $key): ?array
    {
        if (isset($this->cached[$key])) {
            return $this->cached[$key];
        }
        return null;
    }

    /**
     * Setter
     * 
     * @var string $key
     * @var array $value
     * 
     * @return array
     */
    public function set(string $key, array $value): array
    {
        $this->cached[$key] = $value;
        return $value;
    }
}
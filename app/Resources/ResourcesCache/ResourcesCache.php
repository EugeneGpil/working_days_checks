<?php

declare(strict_types = 1);

namespace App\Resources\ResourcesCache;

use Illuminate\Support\Facades\Cache;

class ResourcesCache extends SimpleCache implements ResourcesCacheInterface
{
    /**
     * Getter
     * 
     * @param string $key
     * 
     * @return array|null
     */
    public function get(string $key): ?array
    {
        if (isset($this->cachedRequests[$key])) {
            return $this->cachedRequests[$key];
        }

        $valueFromSimpleCache = parent::get($key);

        if ($valueFromSimpleCache !== null) {
            return $valueFromSimpleCache;
        }

        if (Cache::has($key)) {
            parent::set($key, json_decode(Cache::get($key), true));
            return parent::get($key);
        }

        return null;
    }

    /**
     * Setter
     * 
     * @param string $key,
     * @param array $value,
     * @param int $cacheTime Cache time in minutes
     * 
     * @return array
     */
    public function setex(string $key, array $value, int $cacheTime): array
    {
        parent::set($key, $value);
        Cache::put($key, json_encode($value), $cacheTime);
        return $value;
    }
}
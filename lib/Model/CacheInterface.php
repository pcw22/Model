<?php

interface Model_CacheInterface
{
    /**
     * Returns a cached item.
     * 
     * @param string $key The cache key.
     * 
     * @return mixed
     */
    abstract public function get($key);
    
    /**
     * Caches an item.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The cached vaue.
     * 
     * @return void
     */
    abstract public function set($key, $value);
}
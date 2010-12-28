<?php

/**
 * A cache driver that only caches items in memory for a single execution.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Cache_Static implements Model_CacheInterface
{
    /**
     * The static cache.
     * 
     * @var array
     */
    protected $cache = array();
    
    /**
     * Sets a cache item.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The cache value.
     * 
     * @return Model_Cache_Static
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;
        return $this;
    }
    
    /**
     * Returns an item from the cache.
     * 
     * @param string $key The cache key.
     * 
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        return null;
    }
}
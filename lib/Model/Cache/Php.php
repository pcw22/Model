<?php

namespace Model\Cache;
use Model;

/**
 * A cache driver that only caches items in memory for a single execution.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Php implements Model\CacheInterface
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
     * @return void
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;
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
    
    /**
     * Checks to see if the specified cache item exists.
     * 
     * @param string $key The key to check for.
     * 
     * @return bool
     */
    public function exists($key)
    {
        return isset($this->cache[$key]);
    }
    
    /**
     * Removes the item with the specified key.
     * 
     * @param string $key The key of the item to remove.
     * 
     * @return void
     */
    public function remove($key)
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }
    }
}
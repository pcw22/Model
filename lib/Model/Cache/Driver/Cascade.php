<?php

/**
 * A cache handler that can use multiple cache sources.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Cache_Driver_Cascade implements Model_Cache_DriverInterface
{
    /**
     * The cache sources being used.
     * 
     * @var array
     */
    protected $cache = array();
    
    /**
     * Adds a driver to the handler.
     * 
     * @param Model_Cache_DriverInterface $cache A driver to use.
     * 
     * @return Model_Cache_Driver_Cascade
     */
    public function addDriver(Model_Cache_DriverInterface $cache)
    {
        $this->cache[] = $cache;
        return $this;
    }
    
    /**
     * Adds a cache item to the drivers.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The cache value.
     * 
     * @return Model_Cache_Driver_Cascade
     */
    public function set($key, $value)
    {
        foreach ($this->cache as $cache) {
            $cache->set($key, $value);
        }
        return $this;
    }
    
    /**
     * Returns the first matched item from the cache.
     * 
     * @param string $key The cache key.
     * 
     * @return mixed
     */
    public function get($key)
    {
        foreach ($this->cache as $cache) {
            if ($value = $cache->get($key)) {
                return $value;
            }
        }
        return null;
    }
}
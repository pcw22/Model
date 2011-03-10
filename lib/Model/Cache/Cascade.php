<?php

namespace Model\Cache;
use Model;

/**
 * A cache handler that can use multiple cache sources.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Cascade implements Model\CacheInterface
{
    /**
     * The cache sources being used.
     * 
     * @var array
     */
    protected $cache = array();
    
    /**
     * The cascade configuration.
     * 
     * @var array
     */
    protected $config = array(
        'drivers' => array(
            'Static' => array()
        )
    );
    
    /**
     * Constructs a new cascading cache.
     * 
     * @param array $config The cascade configuration.
     * 
     * @return \Model\Cache\Cascade
     */
    public function __construct(array $config = array())
    {
        $this->config = array_merge($this->config, $config);
        foreach ($this->config['drivers'] as $driver => $config) {
            $this->cache[] = new $driver($config);
        }
    }
    
    /**
     * Adds a cache item to the drivers.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The cache value.
     * 
     * @return void
     */
    public function set($key, $value)
    {
        foreach ($this->cache as $cache) {
            $cache->set($key, $value);
        }
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
    
    /**
     * Checks to see if the specified cache item exists.
     * 
     * @param string $key The key to check for.
     * 
     * @return bool
     */
    public function exists($key)
    {
        foreach ($this->cache as $cache) {
            if ($cache->exists($key)) {
                return true;
            }
        }
        return false;
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
        foreach ($this->cache as $cache) {
            $cache->remove($key);
        }
    }
}
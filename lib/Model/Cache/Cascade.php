<?php

namespace Model\Cache;
use Model\Exception;
use Model\CacheInterface;

/**
 * A cache handler that can use multiple cache sources.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Cascade implements CacheInterface
{
    /**
     * The cache sources being used.
     * 
     * @var array
     */
    protected $cache = array();
    
    /**
     * Constructs a new cascading cache.
     * 
     * @param array $config The cascade configuration.
     * 
     * @return \Model\Cache\Cascade
     */
    public function __construct(array $drivers)
    {
        if (!$drivers) {
            throw new Exception('No cache drivers were specified.');
        }
        
        foreach ($drivers as $driver) {
            if (!$driver instanceof CacheInterface) {
                throw new Exception('Specified drivers must derive from "\Model\CacheInterface".');
            }
            $this->drivers[] = $driver;
        }
    }
    
    /**
     * Adds a cache item to the drivers.
     * 
     * @param string $key      The cache key.
     * @param mixed  $value    The cached value.
     * @param mixed  $lifetime The max lifetime of the item in the cache.
     * 
     * @return void
     */
    public function set($key, $value, $lifetime = null)
    {
        foreach ($this->cache as $cache) {
            $cache->set($key, $value, $lifetime);
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
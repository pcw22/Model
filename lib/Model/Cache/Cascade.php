<?php

/**
 * A cache handler that can use multiple cache sources.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Cache_Cascade implements Model_CacheInterface
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
            'Model_Cache_Static' => array()
        )
    );
    
    /**
     * Constructs a new cascading cache.
     * 
     * @param array $config The cascade configuration.
     * 
     * @return Model_Cache_Cascade
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
<?php

/**
 * The main adapter class. All model adapters should extend this class.
 * 
 * @category Adapters
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Model_Adapter
{
    /**
     * The cache driver, if any, to use for caching.
     * 
     * @var Model_CacheInterface
     */
    protected $cache = null;
    
    /**
     * Saves the passed in entity.
     * 
     * @param Model_Entity $entity The model entity to save.
     * 
     * @return void
     */
    abstract public function save(Model_Entity $entity);
    
    /**
     * If an adapter method is defined as anything but public, it goes through
     * __call which will automate caching if available.
     * 
     * @param string $name The adapter method being called.
     * @param array  $args The arguments passed to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        // first check to see if we have a method
        if (!method_exists($this, $name)) {
            throw new Model_Exception(
                'Call to undefined adapter method: '
                . get_class($this)
                . '->'
                . $name
                . '.'
            );
        }
        
        // then pull from cache if it exists and we have a driver
        if ($this->cache) {
            $key = $this->generateCacheKey(get_class($this), $name, $args);
            if ($value = $this->cache->get($key)) {
                return $value;
            }
        }
        
        // otherwise, get the result from the method
        $result = call_user_func_array(array($this, $name), $args);
        
        // and cache it if we are caching
        if ($this->cache) {
            $this->cache->set($key, $result);
        }
        
        // and just return the result
        return $result;
    }
    
    /**
     * Sets the cache driver to use.
     * 
     * @param Model_CacheInterface $driver The driver to handle the cache.
     * 
     * @return Model_Adapter
     */
    public function setCacheDriver(Model_CacheInterface $driver = null)
    {
        $this->cache = $driver;
        return $this;
    }
    
    /**
     * Generates a cache key based on the parameters passed.
     * 
     * @param string $model  The model classname.
     * @param string $method The method name.
     * @param array  $params The parameters passed to the method.
     * 
     * @return string
     */
    protected function generateCacheKey($model, $method, array $params = array())
    {
        return md5($model . '->' . $method . serialize($params));
    }
}
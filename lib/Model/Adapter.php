<?php

abstract class Model_Adapter
{
    protected $cache = null;
    
    abstract public function save(Model_Entity $entity);
    
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
    
    public function setCacheDriver(Model_CacheInterface $driver = null)
    {
        $this->cache = $driver;
        return $this;
    }
    
    protected function generateCacheKey($model, $method, array $params = array())
    {
        return md5($model . '->' . $method . serialize($params));
    }
}
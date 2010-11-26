<?php

class Model_Cache_Ladder implements Model_CacheInterface
{
    protected $cache = array();
    
    public function addDriver(Model_CacheInterface $cache)
    {
        $this->cache[] = $cache;
        return $this;
    }
    
    public function set($name, $value)
    {
        foreach ($this->cache as $cache) {
            $cache->set($name, $value);
        }
        return $this;
    }
    
    public function get($name)
    {
        foreach ($this->cache as $cache) {
            if ($value = $cache->get($name)) {
                return $value;
            }
        }
        return null;
    }
}
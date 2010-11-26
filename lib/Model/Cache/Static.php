<?php

class Model_Cache_Static implements Model_CacheInterface
{
    protected $cache = array();
    
    public function set($name, $value)
    {
        $this->cache[$name] = $value;
        return $this;
    }
    
    public function get($name)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
        return null;
    }
}
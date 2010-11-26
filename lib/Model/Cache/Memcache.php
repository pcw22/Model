<?php

class Model_Cache_Memcache extends Model_CacheAbstract
{
    protected $config = array(
        'servers' => array(
            array(
                'host' => 'localhost',
                'port' => 11211
            )
        )
    );
    
    protected $memcache;
    
    public function __construct(array $config = array())
    {
        $this->config   = array_merge($this->config, $config);
        $this->memcache = new Memcache;
        foreach ($this->config['servers'] as $server) {
            $this->memcache->addServer($server['host'], $server['port']);
        }
    }
    
    public function set($key, $value)
    {
        return $this->memcache->add($key, $value);
    }
    
    public function get($key)
    {
        return $this->memcache->get($key);
    }
}
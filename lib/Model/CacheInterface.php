<?php

/**
 * The cache driver interface.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Model_CacheInterface
{
    /**
     * Returns a cached item.
     * 
     * @param string $key The cache key.
     * 
     * @return mixed
     */
    public function get($key);
    
    /**
     * Caches an item.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The cached vaue.
     * 
     * @return void
     */
    public function set($key, $value);
}
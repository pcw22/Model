<?php

/**
 * The Base64 strategy.
 * 
 * @category Cache
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Cache_Strategy_Base64 implements Model_Cache_StrategyInterface
{
    /**
     * Encodes the particular value for storing in cache.
     * 
     * @param mixed $value The value to encode.
     * 
     * @return string.
     */
    public function encode($value)
    {
        return base64_encode($value);
    }
    
    /**
     * Decodes the particular value for use in PHP.
     * 
     * @param mixed $value The value to decode.
     * 
     * @return string.
     */
    public function decode($value)
    {
        return base64_decode($value);
    }
}
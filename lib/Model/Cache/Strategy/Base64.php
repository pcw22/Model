<?php

class Model_Cache_Strategy_Base64 implements Model_Cache_StrategyInterface
{
    public function encode($value)
    {
        return base64_encode($value);
    }
    
    public function decode($value)
    {
        return base64_decode($value);
    }
}
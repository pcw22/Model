<?php

class Model_Cache_Strategy_Json implements Model_Cache_StrategyInterface
{
    public function encode($value)
    {
        return json_encode($value);
    }
    
    public function decode($value)
    {
        return json_decode($value);
    }
}
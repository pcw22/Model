<?php

class Model_Cache_Strategy_Serializer implements Model_Cache_StrategyInterface
{
    public function encode($value)
    {
        return serialize($value);
    }
    
    public function decode($value)
    {
        return unserialize($value);
    }
}
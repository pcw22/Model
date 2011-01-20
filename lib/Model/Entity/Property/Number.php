<?php

class Model_Entity_Property_Number extends Model_Entity_Property_Default
{
    protected $value = 0;
    
    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function set($name)
    {
        $number = (int) $number;
        if (is_numeric($this->min) && $this->min > $number) {
            $number = $this->min;
        }
        if (is_numeric($this->max) && $this->max < $number) {
            $number = $this->max;
        }
        $this->value = $number;
    }
}
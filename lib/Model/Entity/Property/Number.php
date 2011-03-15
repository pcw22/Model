<?php

namespace Model\Entity\Property;

/**
 * A property that acts as a number and can have a min/max value.
 * 
 * @category Properties
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Number extends PassThru
{
    /**
     * Overridden to provide a default number.
     * 
     * @var int
     */
    protected $value = 0;
    
    /**
     * Constructs a new number property.
     * 
     * @param string|int $min The minimum value.
     * @param string|int $max The maximum value.
     * 
     * @return \Model\Entity\Property\Number
     */
    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * Sets the number value and casts it accordingly.
     * 
     * @param string|int|float $number The number to set.
     * 
     * @return void
     */
    public function set($number)
    {
        if (strpos($number, '.') !== false) {
            $number = (float) $number;
        } else {
            $number = (int) $number;
        }
        
        if (is_numeric($this->min) && $this->min > $number) {
            $number = $this->min;
        }
        
        if (is_numeric($this->max) && $this->max < $number) {
            $number = $this->max;
        }
        
        $this->value = $number;
    }
}
<?php

/**
 * A property that passes through data.
 * 
 * @category Properties
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Entity_Property_Default implements Model_Entity_PropertyInterface
{
    /**
     * The property value.
     * 
     * @var mixed
     */
	protected $value = null;
    
    /**
     * Sets the value.
     * 
     * @param mixed $value The value to set.
     * 
     * @return void
     */
    public function set($value)
    {
        $this->value = $value;
    }
    
    /**
     * Returns the value.
     * 
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
    
    /**
     * Imports the value.
     * 
     * @param mixed $value The value to import.
     * 
     * @return void
     */
    public function import($value)
    {
    	$this->set($value);
    }
    
    /**
     * Exports the value.
     * 
     * @return mixed
     */
    public function export()
    {
    	return $this->get();
    }
}
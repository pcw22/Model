<?php

/**
 * Outlines what a behavior must implement.
 * 
 * @category Behaviors
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Model_Entity_PropertyInterface
{
    /**
     * Sets the specified value.
     * 
     * @param mixed $value The value to set.
     * 
     * @return void
     */
    public function set($value);
    
    /**
     * Returns the value.
     * 
     * @return mixed
     */
    public function get();
    
    /**
     * Imports the specified value.
     * 
     * @param mixed $value The value to import.
     * 
     * @return void
     */
    public function import($value);

    /**
     * Exports the value.
     * 
     * @return mixed
     */
    public function export();
}
<?php

/**
 * Basic accessible interface defining common interfaces.
 * 
 * @category Accessibility
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Model_Accessible extends ArrayAccess, Countable, Iterator
{
     /**
      * Fills values from an array.
      * 
      * @param mixed $array The values to import.
      * 
      * @return Model_Entity
      */
    public function import($array);
    
    /**
     * Fills the entity with the specified values.
     * 
     * @return Model_Entity
     */
    public function export();
}
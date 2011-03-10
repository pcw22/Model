<?php

namespace Model;

/**
 * Basic accessible interface defining common interfaces.
 * 
 * @category Accessibility
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
interface Accessible extends \ArrayAccess, \Countable, \Iterator, \Serializable
{
     /**
      * Fills values from an array.
      * 
      * @param mixed $array The values to import.
      * 
      * @return \Model\Entity
      */
    public function import($array);
    
    /**
     * Fills the entity with the specified values.
     * 
     * @return \Model\Entity
     */
    public function export();
}
<?php

/**
 * A property that defines a one-to-many relationship with another entity.
 * 
 * @category Properties
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Entity_Property_HasMany extends Model_Entity_Property_HasOne
{
    /**
     * Sets the has many relationship.
     * 
     * @param mixed $value The relationship value.
     * 
     * @return void
     */
    public function set($value)
    {
        // first check to make sure we even have a class specified
        $this->checkForClass();

        // then set the value in the set
        $this->value = new Model_EntitySet($this->class, $value);
    }
}
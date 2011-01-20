<?php

/**
 * A property that defines a one-to-one relationship with another entity.
 * 
 * @category Properties
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Entity_Property_HasOne extends Model_Entity_Property_Default
{
    /**
     * The class name to use for the relationship.
     * 
     * @var string
     */
    protected $class;
    
    /**
     * Constructs a new relationship.
     * 
     * @param string $class The class to use for the relationship.
     * 
     * @return void
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    /**
     * Sets the relationship value.
     * 
     * @param mixed $value The value to set.
     * 
     * @return void
     */
    public function set($value)
    {
        $this->checkForClass();

        // instantiate
        $class = $this->class;
        $class = new $class($value);
        
        // make sure it's a valid instance
        if (!$class instanceof Model_Entity) {
            throw new Model_Exception(
                'The class "'
                . get_class($class)
                . '" must be a subclass of "Model_Entity".'
            );
        }

        // and set
        $this->value = $class;
    }
    
    /**
     * Returns the relationship value.
     * 
     * @return Model_Entity
     */
    public function get()
    {
        // then we can just return it
        return $this->value;
    }
    
    /**
     * Exports the relationship.
     * 
     * @return array
     */
    public function export()
    {
        // we only export if we have data to export
        if ($value = $this->get()) {
            return $value->export();
        }
        return array();
    }
    
    /**
     * Makes sure the specified class is a valid instance.
     * 
     * @throws Model_Exception If it is not a valid instance.
     * 
     * @return void
     */
    protected function checkForClass()
    {
        // make sure a proper class was set
        if (!isset($this->class)) {
            throw new Model_Exception(
                'Cannot instantiate has-one relationship for "'
                . get_class($this->entity)
                . '" because "class" was not defined in the "data" array.'
            );
        }
    }
}
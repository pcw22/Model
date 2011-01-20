<?php

/**
 * The class that represents a set of entities.
 * 
 * @category Entities
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_EntitySet implements Model_Accessible
{
    /**
     * The class used to represent each entity in the set.
     * 
     * @var string
     */
    protected $class;
    
    /**
     * The data containing each entitie.
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * Constructs a new entity set. Primarily used for has many relations.
     * 
     * @param string $class  The class that represents the entities.
     * @param mixed  $values The values to apply.
     * 
     * @return Model_EntitySet
     */
    public function __construct($class, $values = array())
    {
        $this->class = $class;
        $this->import($values);
    }
    
    /**
     * Fills values from an array.
     * 
     * @param mixed $array The values to import.
     * 
     * @return Model_Entity
     */
    public function import($array)
    {
        // make sure the item is iterable
        if (!is_array($array) && !is_object($array)) {
            $array = (array) $array;
        }
        
        // now apply the values
        foreach ($array as $k => $v) {
            $this->offsetSet($k, $v);
        }
        
        return $this;
    }
    
    /**
     * Fills the entity with the specified values.
     * 
     * @param mixed $vals The values to automate the setting of.
     * 
     * @return Model_Entity
     */
    public function export()
    {
        $array = array();
        foreach ($this as $k => $v) {
            $array[$k] = $v->export();
        }
        return $array;
    }
    
    /**
     * Executes the specified callback on each item and places the return value
     * in an array and returns it.
     * 
     * The first argument to the callback is the entity being walked on and the
     * second is any passed in userdata (or an empty array) that is passed by 
     * reference. This allows it to be modified and returned.
     * 
     * @param mixed $callback A callable callback.
     * @param array $userdata Any userdata to pass to the callback.
     * 
     * @return mixed
     */
    public function walk($callback, array &$userdata = array())
    {
        if (!is_callable($callback)) {
            throw new Model_Exception('The callback specified to Model_Entity->walk() is not callable.');
        }
        
        // just call it without returning
        foreach ($this->data as $k => $v) {
            call_user_func($callback, $v, $userdata);
        }
        
        // return the userdata
        return $userdata;
    }
    
    /**
     * Adds or sets an entity in the set. The value is set directly. Only in offsetGet() is the
     * entity instantiated and the value passed to it and then re-set.
     * 
     * @param mixed $offset The offset to set.
     * @param mixed $value  The value to set.
     * 
     * @return Model_Entity
     */
    public function offsetSet($offset, $value)
    {
        // detect offset
        $offset = is_null($offset) ? count($this->data) : $offset;
        
        // apply to data
        $this->data[$offset] = $value;
        
        // chain
        return $this;
    }
    
    /**
     * Returns the entity at the specified offset if it exists. If it doesn't exist
     * then it returns null.
     * 
     * At this point, the entity is instantiated so no unnecessary overhead is used.
     * 
     * @param mixed $offset The offset to get.
     * 
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            // if it's not an entity yet, make it one
            // this will allow the set to not take up any overhead if the item is not accessed
            if (!$this->data[$offset] instanceof $this->class) {
                $class               = $this->class;
                $this->data[$offset] = new $class($this->data[$offset]);
            }
            
            // return the value
            return $this->data[$offset];
        }
        return null;
    }
    
    /**
     * Checks to make sure the specified offset exists.
     * 
     * @param mixed $offset The offset to check for.
     * 
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }
    
    /**
     * Unsets the specified item at the given offset if it exists.
     * 
     * @param mixed $offset The offset to unset.
     * 
     * @return Model_Entity
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
        return $this;
    }
    
    /**
     * Returns the number of entities in the set.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
    
    /**
     * Returns the current element.
     * 
     * @return Model_Entity
     */
    public function current()
    {
        return $this->offsetGet($this->key());
    }
    
    /**
     * Returns the key of the current element.
     * 
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }
    
    /**
     * Moves to the next element.
     * 
     * @return Model_Entity
     */
    public function next()
    {
        next($this->data);
        return $this;
    }
    
    /**
     * Resets to the first element.
     * 
     * @return Model_Entity
     */
    public function rewind()
    {
        reset($this->data);
        return $this;
    }
    
    /**
     * Returns whether or not another element exists.
     * 
     * @return bool
     */
    public function valid()
    {
        return !is_null($this->key());
    }
}
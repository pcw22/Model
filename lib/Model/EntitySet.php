<?php

/**
 * Represents a group of entities from the same collection.
 * 
 * @category Models
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_EntitySet implements ArrayAccess, Countable, Iterator
{
    /**
     * The entity class name used.
     * 
     * @var string
     */
    protected $class;
    
    /**
     * The array of entities in the collection.
     * 
     * @var array
     */
    protected $entities = array();
    
    /**
     * Constructs a new collection.
     * 
     * @param string $class    The class name.
     * @param array  $entities The entities to pass in.
     * 
     * @return Model_EntitySet
     */
    public function __construct($class, $entities = array())
    {
        $this->class = $class;
        if (is_array($entities) || is_object($entities)) {
            foreach ($entities as $entity) {
                $this->offsetSet(null, $entity);
            }
        }
    }
    
    /**
     * Aggregates the values from the specified field.
     * 
     * @param string $field The field to aggregate.
     * 
     * @return array
     */
    public function aggregate($field)
    {
        $values = array();
        foreach ($this->entities as $entity) {
            $values[] = $entity->__get($field);
        }
        return $values;
    }
    
    /**
     * Adds an entity to the set.
     * 
     * @param mixed $offset The offset to set.
     * @param mixed $entity The entity to set.
     * 
     * @return Model_EntitySet
     */
    public function offsetSet($offset, $entity)
    {
        // make sure there is a numeric offset
        if (!is_numeric($offset)) {
            $offset = $this->count();
        }
        
        // for instantiating
        $class = $this->class;
        
        // instantiate and set
        $this->entities[(int) $offset] = new $class($entity);
        
        // chain
        return $this;
    }
    
    /**
     * Returns the entity at the specified offset.
     * 
     * @param mixed $offset The offset to get.
     * 
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->entities[$offset];
        }
        return null;
    }
    
    /**
     * Returns whether or not the specified offset exists.
     * 
     * @param mixed $offset The offset to check for.
     * 
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }
    
    /**
     * Unsets the entity at the specified ofset.
     * 
     * @param mixed $offset The offset to unset.
     * 
     * @return Model_EntitySet
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->entities[$offset];
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
        return count($this->entities);
    }
    
    /**
     * Returns the current entity.
     * 
     * @return Model_Entity
     */
    public function current()
    {
        return current($this->entities);
    }
    
    /**
     * Returns the key of the current entity.
     * 
     * @return int
     */
    public function key()
    {
        return key($this->entities);
    }
    
    /**
     * Moves onto the next entity in the set.
     * 
     * @return Model_EntitySet
     */
    public function next()
    {
        next($this->entities);
        return $this;
    }
    
    /**
     * Resets the entity.
     * 
     * @return Model_EntitySet
     */
    public function rewind()
    {
        reset($this->entities);
        return $this;
    }
    
    /**
     * Checks the validity of the iteration.
     * 
     * @return bool
     */
    public function valid()
    {
        return $this->current() instanceof $this->class;
    }
}
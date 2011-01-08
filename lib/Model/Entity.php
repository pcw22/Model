<?php

/**
 * The main entity class. All model entities should derive from this class.
 * 
 * @category Entities
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Model_Entity implements Model_Accessible
{
    /**
     * Aliases for fields.
     * 
     * @var array
     */
    protected $aliases = array();
    
    /**
     * The data in the entity.
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * Constructs a new entity and sets any passed values.
     * 
     * @param mixed $vals The values to set.
     * 
     * @return Model_Entity
     */
    public function __construct($values = array())
    {
        // pre-construction
        $this->preConstruct();
        
        // automate values
        $this->import($values);
        
        // post-construction
        $this->postConstruct();
    }
    
    /**
     * Easy property setting.
     * 
     * @param string $name The property name.
     * @param mixed  $value The property value.
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        $this->get($name)->set($value);
        return $this;
    }
    
    /**
     * For easy property getting.
     * 
     * @param string $name The property name.
     * 
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name)->get();
    }
    
    /**
     * For easy property checking.
     * 
     * @return bool
     */
    public function __isset($name)
    {
        $name = $this->unalias($name);
        return isset($this->data[$name]);
    }
    
    /**
     * For easy property unsetting.
     * 
     * @param string $name The value to unset.
     * 
     * @return Model_Entity
     */
    public function __unset($name)
    {
        $name = $this->unalias($name);
        if ($this->__isset($name)) {
            unset($this->data[$name]);
        }
        return $this;
    }
    
    /**
     * Sets a property type.
     * 
     * @param string                         $name     The property name.
     * @param Model_Entity_PropertyInterface $property The property value.
     * 
     * @return void
     */
    public function set($name, Model_Entity_PropertyInterface $property)
    {
        $name = $this->unalias($name);
        
        // set the property object and return it
        $this->data[$name] = $property;
        return $this->data[$name];
    }
    
    /**
     * For easy property getting.
     * 
     * @param string $name The property name.
     * 
     * @return mixed
     */
    public function get($name)
    {
        $name = $this->unalias($name);
        
        // if it isn't set yet, set it
        if (!isset($this->data[$name])) {
            $this->data[$name] = new Model_Entity_Property_Default($this);
        }
        
        // and we just return the property object
        return $this->data[$name];
    }

    /**
     * Tells the current entity to behave like the speicfied behavior.
     * 
     * @param Model_Entity_BehaviorInterface $behavior The behavior to behave like.
     * 
     * @return Model_Entity
     */
    public function actAs(Model_Entity_BehaviorInterface $behavior)
    {
        $behavior->init($this);
        return $this;
    }
    
    /**
     * Checks to see if the item exists.
     * 
     * @return bool
     */
    public function exists()
    {
        return $this->__get('_id');
    }
    
    /**
     * Fills the entity with the specified values.
     * 
     * @param mixed $array The array to import.
     * 
     * @return Model_Entity
     */
    public function import($array)
    {
        if (is_array($array) || is_object($array)) {
            foreach ($array as $k => $v) {
                $this->get($k)->import($v);
            }
        } else {
            $this->get('_id')->import($array);
        }
        return $this;
    }
    
    /**
     * Converts the entity to an array.
     * 
     * @param Model_Filter_MapInterface $filter The filter to apply.
     * 
     * @return array
     */
    public function export()
    {
        $array = array();
        foreach ($this->data as $k => $v) {
            $array[$k] = $v->export();
        }
        return $array;
    }
    
    /**
     * Aliases the field with the specified alias.
     * 
     * @param string $field The field to alias.
     * @param string $alias The alias to use.
     * 
     * @return Model_Entity
     */
    public function alias($field, $alias)
    {
        $this->aliases[$alias] = $field;
        return $this;
    }
    
    /**
     * Returns the real name of the specified alias.
     * 
     * @param string $alias The alias to get the real name for.
     * 
     * @return string
     */
    public function unalias($alias)
    {
        if (isset($this->aliases[$alias])) {
            return $this->aliases[$alias];
        }
        return $alias;
    }
    
    /**
     * For setting properties like an array.
     * 
     * @param string $name  The property to set.
     * @param mixed  $value The value to set.
     * 
     * @return Model_Entity
     */
    public function offsetSet($name, $value)
    {
        return $this->__set($name, $value);
    }
    
    /**
     * For getting properties like an array.
     * 
     * @param string $name The property to get.
     * 
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->__get($name);
    }
    
    /**
     * For isset checking using array syntax.
     * 
     * @param string $name The property to check.
     * 
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->__isset($name);
    }
    
    /**
     * For unsetting using array syntax.
     * 
     * @param string $name The property to unset.
     * 
     * @return Model_Entity
     */
    public function offsetUnset($name)
    {
        return $this->__unset($name);
    }
    
    /**
     * Returns the current item in the iteration.
     * 
     * @return mixed
     */
    public function current()
    {
        return $this->__get($this->key());
    }
    
    /**
     * Returns the current key in the iteration.
     * 
     * @return string
     */
    public function key()
    {
        return key($this->data);
    }
    
    /**
     * Moves to the next item in the iteration.
     * 
     * @return Model_Entity
     */
    public function next()
    {
        next($this->data);
        return $this;
    }
    
    /**
     * Resets the iteration.
     * 
     * @return Model_Entity
     */
    public function rewind()
    {
        reset($this->data);
        return $this;
    }
    
    /**
     * Returns whether or not to keep iteration.
     * 
     * @return bool
     */
    public function valid()
    {
        return !is_null($this->key());
    }
    
    /**
     * Counts the number of values in the entity.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
    
    /**
     * Pre-construct event.
     * 
     * @return void
     */
    public function preConstruct()
    {
        
    }
    
    /**
     * Post-construct event.
     * 
     * @return void
     */
    public function postConstruct()
    {
        
    }
    
    /**
     * Pre-insert event.
     * 
     * @return void
     */
    public function preInsert()
    {
        
    }
    
    /**
     * Pre-insert event.
     * 
     * @return void
     */
    public function postInsert()
    {
        
    }
    
    /**
     * Pre-update event.
     * 
     * @return void
     */
    public function preUpdate()
    {
        
    }
    
    /**
     * Post-update event.
     * 
     * @return void
     */
    public function postUpdate()
    {
        
    }
    
    /**
     * Pre-save event.
     * 
     * @return void
     */
    public function preSave()
    {
        
    }
    
    /**
     * Post-save event.
     * 
     * @return void
     */
    public function postSave()
    {
        
    }
    
    /**
     * Pre-remove event.
     * 
     * @return void
     */
    public function preRemove()
    {
        
    }
    
    /**
     * Post-remove event.
     * 
     * @return void
     */
    public function postRemove()
    {
        
    }
}
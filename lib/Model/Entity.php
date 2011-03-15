<?php

namespace Model;
use Model\Entity\BehaviorInterface;
use Model\Entity\Property\PassThru;
use Model\Entity\PropertyInterface;

/**
 * The main entity class. All model entities should derive from this class.
 * 
 * @category Entities
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Entity implements Accessible
{
    /**
     * The data in the entity.
     * 
     * @var array
     */
    private $data = array();
    
    /**
     * The whitelisted properties.
     * 
     * @var array
     */
    private $whitelist = array();
    
    /**
     * The blacklisted properties.
     * 
     * @var array
     */
    private $blacklist = array();
    
    /**
     * Constructs a new entity and sets any passed values.
     * 
     * @param mixed $vals The values to set.
     * 
     * @return \Model\Entity
     */
    public function __construct($values = array())
    {
        $this->preConstruct();
        $this->import($values);
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
        if ($property = $this->get($name)) {
            $property->set($value);
        }
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
        if ($property = $this->get($name)) {
            return $property->get();
        }
        return null;
    }
    
    /**
     * For easy property checking.
     * 
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    
    /**
     * For easy property unsetting.
     * 
     * @param string $name The value to unset.
     * 
     * @return \Model\Entity
     */
    public function __unset($name)
    {
        if ($this->__isset($name)) {
            unset($this->data[$name]);
        }
        return $this;
    }
    
    /**
     * Whitelists a property or properties.
     * 
     * @param mixed $properties A property or array of properties to whitelist.
     * 
     * @return \Model\Entity
     */
    public function whitelist($properties)
    {
        foreach ((array) $properties as $property) {
            $this->whitelist[$property] = $property;
        }
        return $this;
    }
    
    /**
     * Blacklists a property or properties.
     * 
     * @param mixed $properties A property or array of properties to blacklist.
     * 
     * @return \Model\Entity
     */
    public function blacklist($properties)
    {
        foreach ((array) $properties as $property) {
            $this->blacklist[$property] = $property;
        }
        return $this;
    }
    
    /**
     * Sets a property type.
     * 
     * @param string                          $name     The property name.
     * @param \Model\Entity\PropertyInterface $property The property value.
     * 
     * @return \Model\Entity
     */
    public function set($name, PropertyInterface $property)
    {
        if ($this->canAccessProperty($name)) {
            $this->data[$name] = $property;
        }
        return $this;
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
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        
        if ($this->canAccessProperty($name)) {
            $this->data[$name] = new PassThru($this);
        } else {
            throw new Exception('Property "' . get_class($this) . '->' . $name . '" is restricted from being accessed.');
        }
        
        return $this->data[$name];
    }

    /**
     * Tells the current entity to behave like the specified behavior.
     * 
     * @param \Model\Entity\BehaviorInterface $behavior The behavior to behave like.
     * 
     * @return \Model\Entity
     */
    public function actAs(BehaviorInterface $behavior)
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
        return $this->__isset('id');
    }
    
    /**
     * Fills the entity with the specified values.
     * 
     * @param mixed $array The array to import.
     * 
     * @return \Model\Entity
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
     * @return \Model\Entity
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
     * @return \Model\Entity
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
     * @return \Model\Entity
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
     * @return \Model\Entity
     */
    public function next()
    {
        next($this->data);
        return $this;
    }
    
    /**
     * Resets the iteration.
     * 
     * @return \Model\Entity
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
     * Serializes the data and returns it.
     * 
     * @return string
     */
    public function serialize()
    {
        return serialize($this->export());
    }
    
    /**
     * Unserializes and sets the specified data.
     * 
     * @param string The serialized string to unserialize and set.
     * 
     * @return void
     */
    public function unserialize($data)
    {
        $this->import(unserialize($data));
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
    
    /**
     * Checks to see if the property can be set according to whitelist/blacklist restrictions.
     * 
     * @param string $name The property to check.
     * 
     * @return bool
     */
    private function canAccessProperty($name)
    {
        if ($this->whitelist && !isset($this->whitelist[$name])) {
            return false;
        }
        
        if (isset($this->blacklist[$name])) {
            return false;
        }
        
        return true;
    }
}
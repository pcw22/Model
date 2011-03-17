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
     * The name of the id property.
     * 
     * @var string
     */
    private $idPropertyName = 'id';
    
    private $hasOne = array();
    
    private $hasMany = array();
    
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
        if ($this->canAccessProperty($name)) {
            if (isset($this->hasOne[$name]) && !$value instanceof Entity) {
                $class = $this->hasOne[$name];
                $this->data[$name] = new $class($value);
            } elseif (isset($this->hasMany[$name]) && !$value instanceof EntitySet) {
                $this->data[$name] = new EntitySet($this->hasMany[$name], $value);
            } else {
                $this->data[$name] = $value;
            }
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
        if (!$this->canAccessProperty($name)) {
            return null;
        }
        
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        
        if (isset($this->hasOne[$name])) {
            $class = $this->hasOne[$name];
            $this->data[$name] = new $class;
            return $this->data[$name];
        } elseif (isset($this->hasMany[$name])) {
            $this->data[$name] = new EntitySet($this->hasMeny[$name]);
            return $this->data[$name];
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
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
        return $this;
    }
    
    /**
     * Adds a has-one relationship to the entity.
     * 
     * @param string $name  The name of the property.
     * @param string $class The class to use.
     * 
     * @return \Model\Entity
     */
    public function hasOne($name, $class)
    {
        $this->hasOne[$name] = $class;
        return $this;
    }
    
    /**
     * Adds a has-many relationship to the entity.
     * 
     * @param string $name  The name of the property.
     * @param string $class The class to pass to the EntitySet.
     */
    public function hasMany($name, $class)
    {
        $this->hasMany[$name] = $class;
        return $this;
    }
    
    /**
     * Sets the id.
     * 
     * @param mixed $id The id to set.
     * 
     * @return \Model\Entity
     */
    public function setId($id)
    {
        $this->__set($this->idPropertyName, $id);
        return $this;
    }
    
    /**
     * Returns the id.
     * 
     * @return mixed
     */
    public function getId()
    {
        return $this->__get($this->idPropertyName);
    }
    
    /**
     * Returns whether the id is set.
     * 
     * @return bool
     */
    public function hasId()
    {
        return $this->__isset($this->idPropertyName);
    }
    
    /**
     * Removes the id.
     * 
     * @return \Model\Entity
     */
    public function removeId()
    {
        return $this->__unset($this->idPropertyName);
    }
    
    /**
     * Sets the name of the id property.
     * 
     * @param string $name The name of the property.
     * 
     * @return \Model\Entity
     */
    public function setIdPropertyName($name)
    {
        $this->idPropertyName = $name;
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
            if ($property === $this->idPropertyName) {
                throw new Exception('Cannot set id property accessibility.');
            }
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
            if ($property === $this->idPropertyName) {
                throw new Exception('Cannot set id property accessibility.');
            }
            $this->blacklist[$property] = $property;
        }
        return $this;
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
                $this->__set($k, $v);
            }
        } else {
            $this->__set($this->idPropertyName, $array);
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
            if ($v instanceof Accessible) {
                $v = $v->export();
            }
            $array[$k] = $v;
        }
        return $array;
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
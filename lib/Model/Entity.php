<?php

/**
 * The main entity class. All model entities should derive from this class.
 * 
 * @category Entities
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Model_Entity implements ArrayAccess, Countable, Iterator
{
    /**
     * The primary key for this entity.
     * 
     * @var string
     */
    protected $primaryKey;
    
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
     * A flag for flagging which special method is being executed. This is so that
     * magic methods and special methods don't recurse.
     * 
     * @var string
     */
    protected $inSpecialMethod = null;
    
    /**
     * The default primary key for all entities.
     * 
     * @var string
     */
    protected static $defaultPrimaryKey = '_id';
    
    /**
     * The special method types available for calling. Special methods begin with
     * the specified items and are executed through __call.
     * 
     * @var array
     */
    protected $specialMethodTypes = array(
        'set',
        'get',
        'isset',
        'unset'
    );
    
    /**
     * Constructs a new entity and sets any passed values.
     * 
     * @param mixed $vals The values to set.
     * 
     * @return Model_Entity
     */
    public function __construct($vals = array())
    {
        // pre-define the primary key
        $this->primaryKey(self::defaultPrimaryKey());
        
        // pre-construction
        $this->preConstruct();
        
        // apply the values
        if (is_array($vals) || is_object($vals)) {
            foreach ($vals as $k => $v) {
                $this->__set($k, $v);
            }
        }
        
        // post-construction
        $this->postConstruct();
    }
    
    /**
     * Gets called when a special method is called. If any other method is called, then
     * an exception is thrown.
     * 
     * @param string $name The special method.
     * @param array  $args The arguments passed.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        // find out the method type
        $type = null;
        foreach ($this->specialMethodTypes as $special) {
            if ($special === substr($name, 0, strlen($special))) {
                $type = $special;
                break;
            }
        }
        
        // if a special type isn't indicated, then throw an exception
        if (!$type) {
            throw new Model_Exception(
                'Invalid method call to '
                . get_class($this)
                . '->'
                . $name
                . '.'
            );
        }
        
        // find the property and build the special method name
        $prop = substr($name, strlen($type), strlen($name));
        $meth = $type . $prop;
        
        // flag as in a special method
        $this->inSpecialMethod = strtolower($meth);
        
        // format the property
        $prop[0] = strtolower($prop[0]);
        
        // the property name should be the first part of the argument list
        array_unshift($args, $prop);
        
        // call the method and capture the result
        $result = call_user_func_array(array($this, $type), $args);
        
        // un-flag
        $this->inSpecialMethod = null;
        
        // return the special method result
        return $result;
    }
    
    /**
     * For easy setting via property.
     * 
     * @param string $name  The property name.
     * @param mixed  $value The property value.
     * 
     * @return Model_Entity
     */
    public function __set($name, $value)
    {
        $name = $this->unalias($name);
        if ($method = $this->_getMethodNameFor('set', $name)) {
            return $this->$method($value);
        }
        $this->data[$name] = $value;
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
        $name = $this->unalias($name);
        if ($method = $this->_getMethodNameFor('get', $name)) {
            return $this->$method($value);
        }
        if ($this->__isset($name)) {
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
        $name = $this->unalias($name);
        if ($method = $this->_getMethodNameFor('isset', $name)) {
            return $this->$method($value);
        }
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
        if ($method = $this->_getMethodNameFor('unset', $name)) {
            return $this->$method($value);
        }
        if ($this->__isset($name)) {
            unset($this->data[$name]);
        }
        return $this;
    }
    
    /**
     * Sets or returns the primary key. If the key is being set, the old key is 
     * returned. Otherwise the current key is returned.
     * 
     * @param string $key The primary key to use.
     * 
     * @return string
     */
    public function primaryKey($key = null)
    {
        $oldKey = $this->primaryKey;
        if ($key) {
            $this->primaryKey = $key;
        }
        return $oldKey;
    }
    
    /**
     * Checks to see if the item exists.
     * 
     * @return bool
     */
    public function exists()
    {
        return $this->__isset($this->primaryKey());
    }
    
    /**
     * Converts the entity to an array.
     * 
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $k => $v) {
            $array[$k] = $v;
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
     * Gets called prior to saving to ensure the entity is valid.
     * 
     * If the entity is not valid, this method should throw an exception, or return
     * false if a generic exception is desired.
     * 
     * @return mixed
     * 
     * @throws Exception
     */
    public function validate()
    {
        
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
     * Gets the special method name for the specified type using the passed in name.
     * 
     * @param string $type The special method type.
     * @param string $name The name of the original method.
     * 
     * @return string
     */
    private function _getMethodNameFor($type, $name)
    {
        $method = strtolower($type . $name);
        if ($method !== $this->inSpecialMethod && method_exists($this, $method)) {
            return $method;
        }
        return null;
    }
    
    /**
     * Sets or returns the default primary key. If the key is being set, the old
     * key is returned. Otherwise the current default key is returned.
     * 
     * @param string $key The default primary key.
     * 
     * @return string
     */
    public static function defaultPrimaryKey($key = null)
    {
        $oldKey = self::$defaultPrimaryKey;
        if ($key) {
            self::$defaultPrimaryKey = $key;
        }
        return $oldKey;
    }
}
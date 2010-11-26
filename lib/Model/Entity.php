<?php

abstract class Model_Entity implements ArrayAccess, Countable, Iterator
{
    protected $data = array();
    
    protected $inSpecialMethod = null;
    
    protected $specialMethodTypes = array(
        'set',
        'get',
        'has',
        'clear'
    );
    
    public function __construct(array $vals = array())
    {
        foreach ($vals as $k => $v) {
            $this->set($k, $v);
        }
    }
    
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
    
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }
    
    public function &__get($name)
    {
        return $this->get($name);
    }
    
    public function __isset($name)
    {
        return $this->has($name);
    }
    
    public function __unset($name)
    {
        return $this->clear($name);
    }
    
    public function offsetSet($name, $value)
    {
        return $this->set($name, $value);
    }
    
    public function offsetGet($name)
    {
        return $this->get($name);
    }
    
    public function offsetExists($name)
    {
        return $this->has($name);
    }
    
    public function offsetUnset($name)
    {
        return $this->clear($name);
    }
    
    public function current()
    {
        return $this->get($this->key());
    }
    
    public function key()
    {
        return key($this->data);
    }
    
    public function next()
    {
        next($this->data);
        return $this;
    }
    
    public function rewind()
    {
        reset($this->data);
        return $this;
    }
    
    public function valid()
    {
        return !is_null(current($this->data));
    }
    
    public function count()
    {
        return count($this->data);
    }
    
    public function set($name, $value)
    {
        if ($method = $this->_getMethodNameFor('set', $name)) {
            return $this->$method($value);
        }
        $this->data[$name] = $value;
        return $this;
    }
    
    public function get($name)
    {
        if ($method = $this->_getMethodNameFor('get', $name)) {
            return $this->$method($value);
        }
        if ($this->has($name)) {
            return $this->data[$name];
        }
        return null;
    }
    
    public function has($name)
    {
        if ($method = $this->_getMethodNameFor('has', $name)) {
            return $this->$method($value);
        }
        return isset($this->data[$name]);
    }
    
    public function clear($name)
    {
        if ($method = $this->_getMethodNameFor('clear', $name)) {
            return $this->$method($value);
        }
        if ($this->has($name)) {
            unset($this->data[$name]);
        }
        return $this;
    }
    
    public function toArray()
    {
        $array = array();
        foreach ($this as $k => $v) {
            
        }
    }
    
    private function _getMethodNameFor($type, $name)
    {
        $method = strtolower($type . $name);
        if ($method !== $this->inSpecialMethod && method_exists($this, $method)) {
            return $method;
        }
        return null;
    }
}
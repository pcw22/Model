<?php

/**
 * Provides information about return values and parameters for a method.
 * 
 * @category Reflection
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_MethodReflector
{
    /**
     * The reflection method instance.
     * 
     * @var ReflectionMethod
     */
    protected $method;
    
    /**
     * The docblock for this method.
     * 
     * @var array
     */
    protected $docblock;
    
    /**
     * The return types avaialble for this method.
     * 
     * @var array
     */
    protected $returnTypes = array();
    
    /**
     * Constructs a new Method Reflector.
     * 
     * @param mixed $class  The class for the $method.
     * @param mixed $method The method belonging to $class.
     * 
     * @return Model_Method_Reflector
     */
    public function __construct($class, $method)
    {
        $this->method = new ReflectionMethod($class, $method);
    }
    
    /**
     * Returns the return value for the specified method as an array.
     * 
     * @return array
     */
    public function getReturnTypes()
    {
        // if already found, return it
        if ($this->returnTypes) {
            return $this->returnTypes;
        }
        
        // docblock must exist
        if (!$doc = $this->getDocBlock()) {
            return array();
        }
        
        // attempt to get the return part of the docblock
        $doc = explode(' * @return', $doc);
        if (!isset($doc[1])) {
            return array();
        }
        
        // parse out the return types and cache it
        $doc = trim($doc[1]);
        $doc = explode(' ', $doc);
        $doc = explode('|', $doc[0]);
        for ($i = 0; $i < count($doc); $i++) {
            $doc[$i] = trim($doc[$i]);
        }
        $this->returnTypes = $doc;
        
        // return parsed
        return $this->returnTypes;
    }
    
    /**
     * Checks the $value and returns whether or not it is valid when compared
     * to the method return types.
     * 
     * @param mixed $value The value to check against $types.
     * 
     * @return bool
     */
    public function isValidReturnValue($value)
    {
        // get the type of the value
        $valueType = strtolower(gettype($value));
        if ($valueType === 'object') {
            $valueType = get_class($value);
        }
        
        $types = $this->getReturnTypes();
        
        // if there are no types, then it is valid
        if (!$types) {
            return true;
        }
        
        // go through and check each type
        // if it matches one, then it's fine
        foreach ($types as $type) {
            // "mixed" means everything
            if ($type === 'mixed') {
                return true;
            }
            
            // check actual type against specified type
            if ($valueType === $type) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Returns the docblock for the specified method.
     * 
     * @return string|null
     */
    public function getDocBlock()
    {
        // if it's already been retrieved, just return it
        if ($this->docblock) {
            return $this->docblock;
        }
        
        // attempt to get it from the current method
        $docblock = $this->method->getDocComment();
        if ($docblock) {
            $this->docblock = $docblock;
            return $this->docblock;
        }
        
        // if not, check it's interfaces
        $methodName = $this->method->getName();
        foreach ($this->method->getDeclaringClass()->getInterfaces() as $iFace) {
            // coninue of the mehtod doesn't exist in the interface
            if (!$iFace->hasMethod($methodName)) {
                continue;
            }
            
            // attempt to find it in the current interface
            if ($this->docblock = $iFace->getMethod($methodName)->getDocComment()) {
                 break;
            }
        }
        
        return $this->docblock;
    }
}
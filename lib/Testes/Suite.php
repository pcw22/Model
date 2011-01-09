<?php

/**
 * Base class for a group of test classes. Since this class implements the
 * testable interface, there can be multiple levels of test groups and tests.
 * 
 * @category UnitTesting
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Testes_Suite implements Testes_Test_Testable, Iterator, Countable
{
    /**
     * Contains all failed assertions.
     * 
     * @var array
     */
    protected $assertions = array();
    
    /**
     * Contains the tests to be run.
     * 
     * @var array
     */
    protected $classes = array();

    /**
     * Returns the available classes from the files in the current directory.
     * 
     * @return array
     */
    public function getClasses()
    {
        // reflection for getting path and class information
        $self = new ReflectionClass($this);
        
        // get the path
        $path = $self->getFileName();
        $path = str_replace('.php', '', $path);
        
        // get the namespace
        $namespace = $self->getName();
        
        // load each file in the suite by convention
        $classes = array();
        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDir()) {
                continue;
            }
            
            // add the test
            $class     = str_replace('.php', '', $file->getBasename());
            $class     = $namespace . '_' . $class;
            $classes[] = $class;
        }

        return $classes;
    }
    
    /**
     * Returns the name of the current test or test group.
     * 
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
    
    /**
     * Set up event.
     * 
     * @return void
     */
    public function setUp()
    {
        
    }
    
    /**
     * Tear down event.
     * 
     * @return void
     */
    public function tearDown()
    {
        
    }
    
    /**
     * Returns the current test.
     * 
     * @return Testes_Testable
     */
    public function current()
    {
        return current($this->classes);
    }
    
    /**
     * Returns the key of the current test.
     * 
     * @return int
     */
    public function key()
    {
        return key($this->classes);
    }
    
    /**
     * Moves to the next test.
     * 
     * @return void
     */
    public function next()
    {
        next($this->classes);
    }
    
    /**
     * Resets the test iterator.
     * 
     * @return void
     */
    public function rewind()
    {
        reset($this->classes);
    }
    
    /**
     * Returns whether or not the iteration is still valid.
     * 
     * @return bool
     */
    public function valid()
    {
        return is_numeric($this->key());
    }
    
    /**
     * Returns the number of tests in the test/suite.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->classes);
    }
}
<?php

/**
 * Interface for determining if a test or test group is testable.
 * 
 * @category UnitTesting
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Testes_Testable implements Iterator, Countable
{
    /**
     * Contains all test names that passed.
     * 
     * @var array
     */
    private $_passed = array();
    
    /**
     * Contains all test names that are incomplete.
     * 
     * @var array
     */
    private $_incomplete = array();
    
    /**
     * Contains all test names that failed.
     * 
     * @var array
     */
    private $_failed = array();
    
    /**
     * Contains the tests to be run.
     * 
     * @var array
     */
    private $_tests = array();
    
    /**
     * Runs all tests.
     * 
     * @return Testes_Testable
     */
    abstract public function run();
    
    /**
     * Returns the name of the current test or test group.
     * 
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
    
    public function setUp()
    {
        
    }
    
    public function tearDown()
    {
        
    }
    
    public function current()
    {
        return current($this->_tests);
    }
    
    public function key()
    {
        return key($this->_tests);
    }
    
    public function next()
    {
        next($this->_tests);
    }
    
    public function rewind()
    {
        reset($this->_tests);
    }
    
    public function valid()
    {
        return is_numeric($this->key());
    }
        
    public function count()
    {
        return count($this->_tests);
    }
    
    public function passed()
    {
        return $this->_passed;
    }
    
    public function incomplete()
    {
        return $this->_incomplete;
    }
    
    public function failed()
    {
        return $this->_failed;
    }
    
    protected function addPassed($tests)
    {
        foreach ((array) $tests as $test) {
            $this->_passed[] = $test;
        }
        return $this;
    }
    
    protected function addIncomplete($tests)
    {
        foreach ((array) $tests as $test) {
            $this->_incomplete[] = $test;
        }
        return $this;
    }
    
    protected function addFailed($tests)
    {
        foreach ((array) $tests as $test) {
            $this->_incomplete[] = $test;
        }
        return $this;
    }
    
    protected function addTest($test)
    {
        $this->_tests[] = $test;
        return $this;
    }
}
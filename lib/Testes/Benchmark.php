<?php

/**
 * Base test calss. The subclasses only need implement the run method.
 * 
 * @category UnitTesting
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Testes_Benchmark implements Testes_Benchmarkable
{
    /**
     * The method prefix that define benchmarks.
     * 
     * @var int
     */
    const PREFIX = 'benchmark';
    
    /**
     * The failed assertion list.
     * 
     * @var array
     */
    protected $assertions = array();
    
    /**
     * Constructs the test and adds test methods.
     * 
     * @return Testes_Test
     */
    public function __construct()
    {
        $self = new ReflectionClass($this);
        foreach ($self->getMethods() as $method) {
            if (!$method->isPublic() || strpos($method->getName(), self::PREFIX) !== 0) {
                continue;
            }
            $this->tests[] = $method->getName();
        }
    }
    
    /**
     * Runs all test methods.
     * 
     * @return Testes_Test
     */
    public function run()
    {
        $this->setUp();
        foreach ($this->tests as $test) {
            $this->$test();
        }
        $this->tearDown();
        return $this;
    }
    
    /**
     * Sets up the test.
     * 
     * @return void
     */
    public function setUp()
    {
        
    }
    
    /**
     * Tears down the test.
     * 
     * @return void
     */
    public function tearDown()
    {
        
    }
}
<?php

/**
 * Interface that anything that is runable must implement.
 * 
 * @category Benchmarking
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Testes_Runable
{
    /**
     * Runs all tests.
     * 
     * @return Testes_Testable
     */
    public function run();
    
    /**
     * Sets up the test.
     * 
     * @return void
     */
    public function setUp();
    
    /**
     * Tears the test down.
     * 
     * @return void
     */
    public function tearDown();
}
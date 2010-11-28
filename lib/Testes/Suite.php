<?php

/**
 * Base class for a group of test classes. Since this class implements the
 * testable interface, there can be multiple levels of test groups and tests.
 * 
 * @category UnitTesting
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Testes_Suite extends Testes_Testable
{
    /**
     * Constructs the test suite and adds all testable class instances.
     * 
     * @return Testes_Suite
     */
    final public function __construct($path, $prefix)
    {
        $realpath = realpath($path);
        if (!$realpath) {
            throw new Testes_Exception(
                'The path "'
                . $path
                . '" is not a valid path.'
            );
        }
        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDir()) {
                continue;
            }
            
            // include the file once
            include_once $file->getPathname();
            
            $class = str_replace('.php', '', $file->getBasename());
            $class = $prefix . '_' . $class;
            if ($this->isTestSuite($class)) {
                $this->addTest(new $class($file->getPath(), $class));
            } elseif ($this->isTest($class)) {
                $this->addTest(new $class);
            }
        }
    }
    
    /**
     * Runs all tests on each group.
     * 
     * @return mixed
     */
    public function run()
    {
        $this->setUp();
        foreach ($this as $test) {
            $test->run();
            $this->addPassed($test->passed());
            $this->addIncomplete($test->incomplete());
            $this->addFailed($test->failed());
        }
        $this->tearDown();
    }
    
    /**
     * Returns whether or not the specified class is a valid test suite class.
     * 
     * @param mixed $class An instance or string representing the class to check.
     * 
     * @return bool
     */
    protected function isTestSuite($class)
    {
        return is_subclass_of($class, 'Testes_Suite');
    }
    
    /**
     * Returns whether or not the specified class is a valid test class.
     * 
     * @param mixed $class An instance or string representing the class to check.
     * 
     * @return bool
     */
    protected function isTest($class)
    {
        return is_subclass_of($class, 'Testes_Test');
    }
}
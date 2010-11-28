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
abstract class Testes_Test extends Testes_Testable
{
    public function __construct()
    {
        $class = new ReflectionClass($this);
        foreach ($class->getMethods() as $method) {
            if (!$method->isPublic()) {
                continue;
            }
            if (substr($method->getName(), 0, 4) !== 'test') {
                continue;
            }
            $this->addTest($method->getName());
        }
    }
    
    public function run()
    {
        $this->setUp();
        foreach ($this as $test) {
            $result = $this->$test();
            if ($result === true) {
                $this->addPassed($test);
            } elseif ($result === false) {
                $this->addFailed($test);
            } else {
                $this->addIncomplete($test);
            }
        }
        $this->tearDown();
        return $this;
    }
}
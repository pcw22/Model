<?php

/**
 * Interface that anything that is runable must implement.
 * 
 * @category Testing
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Testes_TestSuite extends Testes_Suite implements Testes_Testable
{
	/**
	 * The assertions thrown when running the tests.
	 * 
	 * @var array
	 */
	protected $assertions = array();
    
    /**
     * Runs all tests in the suite. Also handles tears down the suite and
     * failed test before re-throwing the exception.
     * 
     * @return Testes_Suite
     */
    public function run()
    {
        // set up the test suite
        $this->setUp();

        // run each test
        foreach ($this->getClasses() as $test) {
            $test = new $test;
            
            // make sure it implements the correct interface
            if (!$test instanceof Testes_Testable) {
                throw new Testes_Exception(
                    'The test "'
                    . get_class($test)
                    . '" must implement "Testes_Testable".'
                );
            }

            // first set up
            $test->setUp();

            // depending on what is asserted, we hadnle it differently
            try {
                $test->run();
                $this->assertions = array_merge($this->assertions, $test->assertions());
            } catch (Testes_FatalAssertion $e) {
                $this->assertions[] = $e;
                $test->tearDown();
                $this->tearDown();
                return $this;
            } catch (Exception $e) {
                $test->tearDown();
                $this->tearDown();
                throw $e;
            }

            // if we get to this point, only tear down
            $test->tearDown();
        }

        // tear down the suite
        $this->tearDown();

        return $this;
    }
    
    /**
     * Returns the passed tests.
     * 
     * @return array
     */
    public function assertions()
    {
        return $this->assertions;
    }
}
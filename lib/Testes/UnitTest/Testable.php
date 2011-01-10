<?php

/**
 * Interface that all suites and tests must implement.
 * 
 * @category UnitTesting
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Testes_UnitTest_Testable extends Testes_Runable
{
    /**
     * Returns the failed assertions.
     * 
     * @return array
     */
    public function assertions();
}
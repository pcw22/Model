<?php

/**
 * Basic class that will output tests results.
 * 
 * @category Testing
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart
 */
abstract class Testes_UnitTest extends Testes_UnitTest_Suite
{
    /**
     * Converts the test result ot a string.
     * 
     * @return string
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->assertions() as $assertion) {
            $str .= $assertion->getTestClass()
                 .  '->'
                 .  $assertion->getTestMethod()
                 .  '() - ' 
                 .  $assertion->getMessage()
                 .  Testes_Output::breaker();
        }
        return $str ? $str : 'All tests passed!';
    }
}
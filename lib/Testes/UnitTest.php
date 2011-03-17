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
        if (Testes_Output::isCli()) {
            $str .= Testes_Output::breaker();
        }
        
        if ($assertions = $this->assertions()) {
            foreach ($this->assertions() as $assertion) {
                $str .= $assertion->getTestClass()
                     .  '->'
                     .  $assertion->getTestMethod()
                     .  '() on line '
                     .  $assertion->getTestLine()
                     .  ': '
                     .  $assertion->getMessage()
                     .  Testes_Output::breaker();
            }
        } else {
            $str .= 'All tests passed!' . Testes_Output::breaker();
        }
        
        if (Testes_Output::isCli()) {
            $str .= Testes_Output::breaker();
        }
        
        return $str;
    }
}
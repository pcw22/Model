<?php

/**
 * Converts the output of a benchmark suite to a string.
 * 
 * @category Benchmarking
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Testes_Benchmark extends Testes_Benchmark_Suite
{
    /**
     * Converts the benchmark result ot a string.
     * 
     * @return string
     */
    public function __toString()
    {
        $str = '';
        $br  = Testes_Output::breaker();
        $sp1 = Testes_Output::spacer(1);
        $sp2 = Testes_Output::spacer(2);
        $sp3 = Testes_Output::spacer(3);
        $sp4 = Testes_Output::spacer(4);

        if (!Testes_Output::isCli()) {
            $str .= '<pre>';
        }

        foreach ($this->results() as $suite => $benchmarks) {
        	$str .= $suite . $br;
        	foreach ($benchmarks as $benchmark => $result) {
        		$str .= $sp2 . $benchmark . $br
        		     .  $sp4 . 'memory' . $sp1 . ':' . $sp1 . round($result['memory'] / 1024 / 1024, 3) . ' MB' . $br
        		     .  $sp4 . 'time' . $sp3 . ':' . $sp1 . round($result['time'], 3) . ' seconds' . $br;
        	}
        }

        if (!Testes_Output::isCli()) {
            $str .= '</pre>';
        }

        return $str;
    }
}
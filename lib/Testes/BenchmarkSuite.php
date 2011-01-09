<?php

/**
 * Interface that anything that is runable must implement.
 * 
 * @category Benchmarking
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Testes_BenchmarkSuite extends Testes_Suite implements Testes_Benchmarkable
{
    /**
     * Runs all benchmarks in the suite.
     * 
     * @return Testes_Benchmark_Suite
     */
    public function run()
    {
        // set up the test suite
        $this->setUp();

        // run each test
        foreach ($this->getClasses() as $bench) {
            $bench = new $bench;
            
            // make sure it implements the correct interface
            if (!$bench instanceof Testes_Benchmarkable) {
                throw new Testes_Exception(
                    'The test "'
                    . get_class($bench)
                    . '" must implement "Testes_Benchmarkable".'
                );
            }

            // first set up
            $bench->setUp();
            $bench->run();
            $bench->tearDown();
        }

        // tear down the suite
        $this->tearDown();

        return $this;
    }
}
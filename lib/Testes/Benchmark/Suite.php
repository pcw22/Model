<?php

/**
 * Interface that anything that is runable must implement.
 * 
 * @category Benchmarking
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Testes_Benchmark_Suite extends Testes_Suite implements Testes_Benchmark_Benchmarkable
{
    /**
     * The results of the benchmark.
     * 
     * @return array
     */
    protected $results = array();

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
            // instantiate and run
            $bench = new $bench;
            $bench->setUp();
            $bench->run();
            $bench->tearDown();

            // add up the results
            $this->results = array_merge($this->results, $bench->results());
        }

        // tear down the suite
        $this->tearDown();

        return $this;
    }

    /**
     * Returns the results of the benchmark.
     * 
     * @return array
     */
    public function results()
    {
        return $this->results;
    }
}
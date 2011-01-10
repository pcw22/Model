<?php

/**
 * Interface that all suites and tests must implement.
 * 
 * @category Benchmarking
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Testes_Benchmark_Benchmarkable extends Testes_Runable
{
	/**
	 * Returns the results of the benchmark.
	 * 
	 * @return array
	 */
	public function results();
}
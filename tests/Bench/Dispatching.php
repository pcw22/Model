<?php

class Bench_Dispatching extends Testes_Benchmark_Test
{
    protected $dispatcher;
    
    public function setUp()
    {
        $this->dispatcher = new \Model\Dispatcher(
            new Provider_Mock_Content,
            'Provider_Content'
        );
    }
    
    public function methodCallingWithDocblock1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->dispatcher->findById(1);
        }
    }
    
    public function methodCallingWithNoDocblock1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->dispatcher->findByIdNoType(1);
        }
    }
}
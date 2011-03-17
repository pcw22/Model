<?php

use Provider\ContentEntity;
use Provider\ContentRepository;

class Bench_RepositoryMethodCalling extends Testes_Benchmark_Test
{
    private $entities = array();
    
    private $repo;
    
    public function setUp()
    {
        $this->repo = new ContentRepository;
    }
    
    public function callingInsert1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $entity = new ContentEntity($i);
            $this->repo->insert($entity);
            $this->entities[] = $entity;
        }
    }
    
    public function callingUpdate1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->repo->update($this->entities[$i]);
        }
    }
    
    public function callingSave1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->repo->save($this->entities[$i]);
        }
    }
    
    public function callingRemove1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->repo->remove($this->entities[$i]);
        }
    }
}
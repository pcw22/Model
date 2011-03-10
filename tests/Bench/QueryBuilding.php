<?php

use Model\Query;

class Bench_QueryBuilding extends Testes_Benchmark_Test
{
    public function simple1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $query = new Query;
            $query->from('test')->eq('test', 1);
            $query->compile();
            unset($query);
        }
    }
    
    public function complex1000Times()
    {
        for ($i = 0; $i < 1000; $i++) {
            $query = new Query;
            $query->from('user u')->leftJoin('person p', 'u.id = p.idUser')->eq('u.id', 1)->page(10, 3)->sort('u.name, u.birthday');
            $query->compile();
            unset($query);
        }
    }
}
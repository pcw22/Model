<?php

use Model\EntitySet;
use Provider\ContentEntity;

class Bench_EntityHydration extends Testes_Benchmark_Test
{
    private $set;
    
    public function setUp()
    {
        $this->set = new EntitySet('\Provider\ContentEntity');
    }
    
    public function hydrate1000Times()
    {
        $user = array(
            'id'      => 1,
            'name'    => 'Trey Shugart',
            'dob'     => '1983-01-02',
            'created' => time(),
            'updated' => time()
        );
        
        $comments = array(
            array(
                'name'    => 'Trey Shugart',
                'email'   => 'treshugart@gmail.com',
                'subject' => 'Test Subject',
                'body'    => 'Comment body.'
            ),
            array(
                'name'    => 'Trey Shugart',
                'email'   => 'treshugart@gmail.com',
                'subject' => 'Test Subject',
                'body'    => 'Comment body.'
            ),
            array(
                'name'    => 'Trey Shugart',
                'email'   => 'treshugart@gmail.com',
                'subject' => 'Test Subject',
                'body'    => 'Comment body.'
            ),
            array(
                'name'    => 'Trey Shugart',
                'email'   => 'treshugart@gmail.com',
                'subject' => 'Test Subject',
                'body'    => 'Comment body.'
            ),
            array(
                'name'    => 'Trey Shugart',
                'email'   => 'treshugart@gmail.com',
                'subject' => 'Test Subject',
                'body'    => 'Comment body.'
            )
        );
        
        for ($i = 0; $i < 1000; $i++) {
            $this->set[] = new ContentEntity(array(
                'id'       => 1,
                'title'    => 'Content 1',
                'created'  => time(),
                'updated'  => time(),
                'user'     => $user,
                'comments' => $comments
            ));
        }
    }
    
    public function exportHydrated()
    {
        $this->set->export();
    }
}
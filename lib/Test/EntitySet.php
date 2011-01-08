<?php

class Test_EntitySet extends Testes_Test
{
    protected $set;
    
    public function testConstruction()
    {
        // instantiate with values
        $this->set = new Model_EntitySet('Content', array(array('title' => 'element 1')));
        
        // make sure it has one
        $this->assert(count($this->set) === 1, 'The set should have 1 element');
    }
    
    public function testArrayAccess()
    {
        // make sure it is an instance of Content
        $this->assert($this->set[0] instanceof Content, 'The first element should be an instance of "Content".');
        
        // check for id setting
        $this->set[] = 'myid';
        $this->assert($this->set[1]->_id === 'myid', 'The second element should only have an id set.');
        
        // check isset
        $this->assert(isset($this->set[1]), 'The second element should be set.');
        
        // check unset
        $copy = $this->set[1];
        unset($this->set[1]);
        $this->assert(!isset($set[1]), 'The second element should be unset.');
        
        // reset the copy for further tests
        $this->set[1] = $copy;
    }
    
    public function testIterator()
    {
        // make a copy
        $test = array();
        foreach ($this->set as $item) {
            $test[] = $item;
        }
        
        // test to make sure they are correct instances
        $this->assert($test[0] instanceof Content && $test[1] instanceof Content, 'Both items should be an instance of "Content".');
    }
    
    public function testExport()
    {
        $array = $this->set->export();
        foreach ($array as $item) {
            $this->assert(is_array($item), 'The items are not arrays and should be.');
        }
    }
}
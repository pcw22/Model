<?php

class Test_Dispatcher extends Testes_Test
{
    /**
     * Ensures that the save method is properly defined and a valid object is passed.
     * 
     * @return void
     */
    public function testSave()
    {
        $mock = new Model_Dispatcher(new Mock_Content, 'Content');
        
        // try saving of good instances
        try {
            $mock->save(new Content);
            $mock->save(array());
        } catch (Exception $e) {
            $this->assert(false, 'Could not save with message: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensures that the insert method is properly defined and a valid object is passed.
     * 
     * @return void
     */
    public function testInsert()
    {
        $mock = new Model_Dispatcher(new Mock_Content, 'Content');
        
        // try saving of good instances
        try {
            $mock->insert(new Content);
            $mock->insert(array());
        } catch (Exception $e) {
            $this->assert(false, 'Could not insert with message: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensures that the update method is properly defined and a valid object is passed.
     * 
     * @return void
     */
    public function testUpdate()
    {
        $mock = new Model_Dispatcher(new Mock_Content, 'Content');
        
        // try saving of good instances
        try {
            $mock->update(new Content);
            $mock->update(array());
        } catch (Exception $e) {
            $this->assert(false, 'Could not update with message: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensures that the remove method is properly defined and a valid object is passed.
     * 
     * @return void
     */
    public function testRemove()
    {
        $mock = new Model_Dispatcher(new Mock_Content, 'Content');
        
        // try saving of good instances
        try {
            $mock->remove(new Content);
            $mock->remove(array());
        } catch (Exception $e) {
            $this->assert(false, 'Could not remove with message: ' . $e->getMessage());
        }
    }
    
    /**
     * Tests to make sure caching takes over when methods are called more than once.
     * 
     * @return void
     */
    public function testCaching()
    {
        $mock = Model::get()->content;
        $mock->findById(1);
        $mock->findById(1);
        $mock->findById(2);
        $mock->findById(2);
        
        $this->assert(Mock_Content::$called === 2, 'Caching did not takeover on Mock_Content->findById().');
    }
}
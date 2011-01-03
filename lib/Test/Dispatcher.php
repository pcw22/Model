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
        try {
            $mock = new Model_Dispatcher(new Mock_Content);
            $mock->save(new Content);
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
        try {
            $mock = new Model_Dispatcher(new Mock_Content);
            $mock->insert(new Content);
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
        try {
            $mock = new Model_Dispatcher(new Mock_Content);
            $mock->update(new Content);
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
        try {
            $mock = new Model_Dispatcher(new Mock_Content);
            $mock->remove(new Content);
        } catch (Exception $e) {
            $this->assert(false, 'Could not save with message: ' . $e->getMessage());
        }
    }
    
    /**
     * Tests to make sure caching takes over when methods are called more than once.
     * 
     * @return void
     */
    public function testCaching()
    {
        $mock = Model::getInstance()->content;
        $mock->findById(1);
        $mock->findById(1);
        $mock->findById(2);
        $mock->findById(2);
        
        $this->assert(Mock_Content::$called === 2, 'Caching did not takeover on Mock_Content->findById().');
    }
}
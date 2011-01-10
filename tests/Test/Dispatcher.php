<?php

class Test_Dispatcher extends Testes_UnitTest_Test
{
    /**
     * Ensures that the save method is properly defined and a valid object is passed.
     * 
     * @return void
     */
    public function testSave()
    {
        $mock = new Model_Dispatcher(new Provider_Mock_Content, 'Provider_Content');
        
        // try saving of good instances
        try {
            $mock->save(new Provider_Content);
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
        $mock = new Model_Dispatcher(new Provider_Mock_Content, 'Provider_Content');
        
        // try saving of good instances
        try {
            $mock->insert(new Provider_Content);
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
        $mock = new Model_Dispatcher(new Provider_Mock_Content, 'Provider_Content');
        
        // try saving of good instances
        try {
            $mock->update(new Provider_Content);
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
        $mock = new Model_Dispatcher(new Provider_Mock_Content, 'Provider_Content');
        
        // try saving of good instances
        try {
            $mock->remove(new Provider_Content);
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
        
        $this->assert(Provider_Mock_Content::$called === 2, 'Caching did not takeover on Provider_Mock_Content->findById().');
    }
}
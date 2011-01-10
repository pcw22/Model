<?php

class Test_Entity extends Testes_UnitTest_Test
{
    /**
     * Sets up the entity test.
     * 
     * @return void
     */
    public function setUp()
    {
        
    }
    
    /**
     * Validates all parameters that can be passed to the constructor.
     * 
     * @return void
     */
    public function testConstruction()
    {
        $import  = array('id' => true);
        $content = new Provider_Content($import);
        
        // test array filling
        $this->assert($content->id === true, 'The id was not set.');
    }
    
    public function testAliasing()
    {
        $content     = new Provider_Content;
        $content->id = 'aliastest';
        $this->assert($content->_id === 'aliastest', 'Aliasing is not working.');
    }
    
    public function testExists()
    {
        $content      = new Provider_Content;
        $content->_id = 'blacksheep';
        $this->assert($content->exists(), 'After setting an id, the entity should exist.');
    }
    
    /**
     * Tests to make sure all events are triggered.
     * 
     * @return void
     */
    public function testEvents()
    {
        // list of events to assert
        $events = array(
            'preConstruct',
            'postConstruct',
            'preSave',
            'postSave',
            'preInsert',
            'postInsert',
            'preUpdate',
            'postUpdate',
            'preRemove',
            'postRemove'
        );
        
        // mock objects
        $mock     = Model::get()->content;
        $content1 = new Provider_Content;
        $content2 = array();
        
        // save once to insert, save twice to update
        $content1 = $mock->save($content1);
        $content1 = $mock->save($content1);
        $content1 = $mock->remove($content1);
        
        // test array
        $content2 = $mock->save($content2);
        $content2 = $mock->save($content2);
        $content2 = $mock->remove($content2);
        
        // make sure valid instances were returned
        $this->assert($content1 instanceof Provider_Content, 'An instance is not returned when saving an instance');
        $this->assert($content2 instanceof Provider_Content, 'An instance is not returned when saving an array.');
        
        // assert events
        foreach ($events as $event) {
            $this->assert($content1->$event, 'Model_Entity->' . $event . '() was not triggered.');
        }
    }
}
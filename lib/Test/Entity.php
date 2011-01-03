<?php

class Test_Entity extends Testes_Test
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
        $fill1    = array('id' => true);
        $fill2    = (object) $fill1;
        $content1 = new Content($fill1);
        $content2 = new Content($fill2);
        
        // test array filling
        $this->assert($content1->id === true, 'The id was not set.');
        
        // test object filling
        $this->assert($content2->id === true, 'The id was not set.');
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
        $mock    = Model::getInstance()->content;
        $content = new Content;
        
        // save once to insert, save twice to update
        $mock->save($content);
        $mock->save($content);
        $mock->remove($content);
        
        // assert events
        foreach ($events as $event) {
            $this->assert($content->$event, 'Model_Entity->' . $event . '() was not triggered.');
        }
    }
}
<?php

class Test_Model extends Testes_Test
{
    /**
     * Set up the model test.
     * 
     * @return void
     */
    public function setUp()
    {
        Model::setInstance(null, new Model);
        Model::setInstance('custom', new Model);
    }
    
    /**
     * Makes sure instances are set properly.
     * 
     * @return void
     */
    public function testInstanceSetting()
    {
        $this->assert(Model::hasInstance('default'), 'The model does not have a default instance.');
        $this->assert(Model::hasInstance('custom'), 'The model does not have a custom instance.');
    }
    
    /**
     * Make sure a default instance can be rolled back.
     * 
     * @return void
     */
    public function testDefaultInstance()
    {
        // set a different default name
        Model::setDefaultInstance('custom');
        
        // and test it
        $hasDefault = Model::getDefaultInstance() === 'custom';
        
        // and reset it
        Model::setDefaultInstance('default');
        
        // assert
        $this->assert($hasDefault, 'Could not change the default instance to another instance.');
    }
    
    /**
     * Tests whehter or not an instance can be removed.
     * 
     * @return void
     */
    public function testInstanceRemoving()
    {
        // before we remove
        $hasBefore = Model::hasInstance('custom');
        
        // now remove
        Model::removeInstance('custom');
        
        // and re-check
        $hasAfter = Model::hasInstance('custom');
        
        // make sure it existed before and it doesn't exist after
        $this->assert($hasBefore, 'There was no custom instance to begin with.');
        $this->assert(!$hasAfter, 'The custom instance could not be remove.');
    }
    
    /**
     * Tests whehter or not the proper driver is returned using the getter methods.
     * 
     * @return void
     */
    public function testDriverGetting()
    {
        $this->assert(
            Model::getInstance()->content->getDriver() instanceof Mock_Content,
            'The content adapter is the incorrect instance.'
        );
    }
}
<?php

class Test_Model extends Testes_UnitTest_Test
{
    /**
     * Set up the model test.
     * 
     * @return void
     */
    public function setUp()
    {
        Model::set(null, new Model);
        Model::set('custom', new Model);
    }
    
    /**
     * Makes sure instances are set properly.
     * 
     * @return void
     */
    public function testInstanceSetting()
    {
        $this->assert(Model::has('default'), 'The model does not have a default instance.');
        $this->assert(Model::has('custom'), 'The model does not have a custom instance.');
    }
    
    /**
     * Make sure a default instance can be rolled back.
     * 
     * @return void
     */
    public function testDefaultInstance()
    {
        // set a different default name
        Model::setDefault('custom');
        
        // and test it
        $hasDefault = Model::getDefault() === 'custom';
        
        // and reset it
        Model::setDefault('default');
        
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
        $hasBefore = Model::has('custom');
        
        // now remove
        Model::remove('custom');
        
        // and re-check
        $hasAfter = Model::has('custom');
        
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
            Model::get()->content->getDriver() instanceof Provider_Mock_Content,
            'The content adapter is the incorrect instance.'
        );
    }
}
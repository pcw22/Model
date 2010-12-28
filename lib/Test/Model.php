<?php

class Test_Model extends Testes_Test
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'adapter' => 'Mock',
                'format'  => ':name_:adapter'
            )
        );
        Model::setInstance(null, new Model);
        Model::setInstance('custom', new Model);
    }
    
    public function testInstanceSetting()
    {
        $this->assert(Model::hasInstance('default'), 'The model does not have a default instance.');
        $this->assert(Model::hasInstance('custom'), 'The model does not have a custom instance.');
    }
    
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
    
    public function testInstanceClearing()
    {
        // before we clear
        $hasBefore = Model::hasInstance('custom');
        
        // now clear
        Model::clearInstance('custom');
        
        // and re-check
        $hasAfter = Model::hasInstance('custom');
        
        // make sure it existed before and it doesn't exist after
        $this->assert($hasBefore, 'There was no custom instance to begin with.');
        $this->assert(!$hasAfter, 'The custom instance could not be cleared.');
    }
    
    public function testAdapterGetting()
    {
        $this->assert(
            Model::getInstance()->content instanceof Content_Mock,
            'The content adapter is the incorrect instance when using "__get()".'
        );
        
        $this->assert(
            Model::getInstance()->get('content') instanceof Content_Mock,
            'The content adapter is the incorrect instance when using "get()".'
        );
    }
}

class Content extends Model_Entity
{
    
}

class Content_Mock extends Model_Adapter
{
    public function save(Model_Entity $entity)
    {
        
    }
}
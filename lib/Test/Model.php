<?php

class Test_Model extends Testes_Test
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'adapter' => 'Mysql',
                'format'  => ':name_:adapter'
            )
        );
        Model::setInstance(null, new Model);
        Model::setInstance('custom', new Model);
    }
    
    public function testInstanceSetting()
    {
        return Model::hasInstance('default')
            && Model::hasInstance('custom');
    }
    
    public function testDefaultInstance()
    {
        // set a different default name
        Model::setDefaultInstance('custom');
        
        // and test it
        $hasDefault = Model::getDefaultInstance() === 'custom';
        
        // and reset it
        Model::setDefaultInstance('default');
        
        // then test it
        return $hasDefault;
    }
    
    public function testInstanceClearing()
    {
        // before we clear
        $hasBefore = Model::hasInstance('custom');
        
        // now clear
        Model::clearInstance('custom');
        
        // and re-check
        $hasAfter = Model::hasInstance('custom');
        
        // check before and after
        return $hasBefore && !$hasAfter;
    }
    
    public function testAdapterGetting()
    {
        return Model::getInstance()->content instanceof Content_Mysql
            && Model::getInstance()->get('content') instanceof Content_Mysql;
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
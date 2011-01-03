<?php

class Test extends Testes_Suite
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'driver'       => 'Mock',
                'format'       => ':driver_:name',
                'cache.driver' => 'Model_Cache_Static'
            )
        );
    }
}



class Content extends Model_Entity
{
    public $preConstruct = false;
    
    public $postConstruct = false;
    
    public $preInsert = false;
    
    public $postInsert = false;
    
    public $preUpdate = false;
    
    public $postUpdate = false;
    
    public $preSave = false;
    
    public $postSave = false;
    
    public $preRemove = false;
    
    public $postRemove = false;
    
    public function setName($name)
    {
        // split
        $parts = explode(' ');
        
        // apply
        $this->data['forename'] = $parts[0];
        $this->data['surname']  = isset($parts[1]) ? $parts[1] : null;
    }
    
    public function preConstruct()
    {
        $this->preConstruct = true;
        $this->alias('_id', 'id');
    }
    
    public function postConstruct()
    {
        $this->postConstruct = true;
    }
    
    public function preInsert()
    {
        $this->preInsert = true;
    }
    
    public function postInsert()
    {
        $this->postInsert = true;
    }
    
    public function preUpdate()
    {
        $this->preUpdate = true;
    }
    
    public function postUpdate()
    {
        $this->postUpdate = true;
    }
        
    public function preSave()
    {
        $this->preSave = true;
    }
    
    public function postSave()
    {
        $this->postSave = true;
    }
    
    public function preRemove()
    {
        $this->preRemove = true;
    }
    
    public function postRemove()
    {
        $this->postRemove = true;
    }
}

class Mock_Content implements Model_DriverInterface
{
    public static $called = 0;
    
    public function findById($id)
    {
        self::$called++;
        return new Content(array('id' => $id, 'title' => 'test ' . $id));
    }
    
    public function insert(Model_Entity $entity)
    {
        $entity->id = md5(microtime());
    }
    
    public function update(Model_Entity $entity)
    {
        
    }
    
    public function remove(Model_Entity $entity)
    {
        unset($entity->id);
    }
}
<?php

class Test extends Testes_Suite
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'driver'       => 'Mock',
                'driver.class' => ':driver_:name',
                'cache.class'  => 'Model_Cache_Static'
            )
        );
    }
    /**
     * Converts the test result ot a string. Detects CLI and formats
     * according to which interface is calling the tests.
     * 
     * @return string
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->assertions() as $assertion) {
            $str .= $assertion->getTestClass()
                 .  '->'
                 .  $assertion->getTestMethod()
                 .  '() - ' 
                 .  $assertion->getMessage()
                 .  $this->getBreaker();
        }
        return $str ? $str : 'All tests passed!';
    }
    
    /**
     * Returns the line break element depending on if the test was
     * accessed via CLI or web.
     * 
     * @return string
     */
    protected function getBreaker()
    {
        if (defined('STDIN')) {
            return "\n";
        }
        return '<br />';
    }
}



class Bad extends Model_Entity
{
    
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
    
    public function preConstruct()
    {
        $this->preConstruct = true;
        $this->actAs(new Behavior_Default);
        $this->actAs(new Behavior_Content);
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

class User extends Model_Entity
{
    public function preConstruct()
    {
        $this->actAs(new Behavior_Default);
        $this->actAs(new Behavior_Person);
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
    
    public function insert($entity)
    {
        $entity->id = md5(microtime());
    }
    
    public function update($entity)
    {
        
    }
    
    public function remove($entity)
    {
        unset($entity->id);
    }
}

class Behavior_Default implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->alias('_id', 'id');
    }
}

class Behavior_Content implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->set('user', new Model_Entity_Property_HasOne($entity, array('class' => 'User')));
        $entity->set('created', new Model_Entity_Property_Date($entity));
        $entity->set('updated', new Model_Entity_Property_Date($entity));
    }
}

class Behavior_Person implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->set('name', new Model_Entity_Property_Name($entity));
        $entity->set('dob', new Model_Entity_Property_Date($entity));
    }
}
<?php

class Provider_Mock_Content implements Model_DriverInterface
{
    public static $called = 0;
    
    public function findById($id)
    {
        self::$called++;
        return new Provider_Content(array('id' => $id, 'title' => 'test ' . $id));
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
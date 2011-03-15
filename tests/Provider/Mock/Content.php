<?php

class Provider_Mock_Content implements Provider_ContentInterface
{
    public static $called = 0;
    
    public function __construct()
    {
        self::$called = 0;
    }
    
    public function findById($id)
    {
        self::$called++;
        return new Provider_Content(array('id' => $id, 'title' => 'test ' . $id));
    }
    
    public function findByIdNoType($id)
    {
        self::$called++;
        return new Provider_Content(array('id' => $id, 'title' => 'test ' . $id));
    }
    
    public function insert($entity)
    {
        return md5(microtime());
    }
    
    public function update($entity)
    {
        
    }
    
    public function remove($entity)
    {
        
    }
    
    public function getEntityClassName()
    {
        return 'Provider_Content';
    }
}
<?php

namespace Provider;
use Model\Repository;

class UserRepository extends Repository
{
    /**
     * Keeps track of the number of times "findById()" was called so we can test
     * if an item was cached or not.
     * 
     * @var int
     */
    public $findByIdCallCount = 0;
    
    /**
     * Mimics data storage for the entities.
     * 
     * @var array
     */
    private $entities = array();
    
    public function findById($id)
    {
        // if it is found in cache, return it
        if ($cache = $this->retrieve(__FUNCTION__, func_get_args())) {
            return $cache;
        }
        
        if (isset($this->entities[$id])) {
            $entity = $this->entities[$id];
            $this->persist(__FUNCTION__, func_get_args(), $entity);
        } else {
            $entity = false;
        }
        
        // keep track of the number of times this method was called for testing
        ++$this->findByIdCallCount;
        
        return $entity;
    }
    
    protected function insert(UserEntity $content)
    {
        // generate an id
        $content->id = md5(microtime());
        
        // store in entity storage based on id
        $this->entities[$content->id] = $content;
        
        // store in cache for the specified method
        $this->persist('findById', array($content->id), $content);
    }
    
    protected function update(UserEntity $content)
    {
        // make sure that it exists first as it can only be updated if it already exists
        // mimics database behavior
        if (!isset($this->entities[$content->id])) {
            throw new \Exception('User was does not exists, therefore it was not updated.');
        }
        
        // update the stored entity
        $this->entities[$content->id] = $content;
        
        // update the cache
        $this->persist('findById', array($content->id), $content);
    }
    
    protected function remove(UserEntity $content)
    {
        // expire the cache
        $this->expire('findById', array($content->id));
        
        // then remove the item from the storage property
        unset($this->entities[$content->id]);
    }
}
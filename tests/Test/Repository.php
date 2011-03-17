<?php

use Provider\ContentEntity;
use Provider\ContentRepository;
use Provider\UserEntity;
use Provider\UserRepository;
use Model\Cache\Php as Cache;

class Test_Repository extends Testes_UnitTest_Test
{
    public function wrongMethodCallCatching()
    {
        $repo = new ContentRepository;
        try {
            $repo->someUndefinedMethod();
            $this->assert(false, 'Undefined method not caught.');
        } catch (\Exception $e) {
            
        }
    }
    
    public function inserting()
    {
        $repo = new ContentRepository;
        try {
            $entity = $repo->insert(new ContentEntity(array(
                'name' => 'Trey Shugart'
            )));
        } catch (\Exception $e) {
            $this->assert(false, 'Id was not returned by insert method.');
        }
        
        if (!$entity->preSave) {
            $this->assert(false, 'Entity preSave event was not triggered.');
        }
        
        if (!$entity->postSave) {
            $this->assert(false, 'Entity postSave event was not triggered.');
        }
        
        if (!$entity->preInsert) {
            $this->assert(false, 'Entity preInsert event was not triggered.');
        }
        
        if (!$entity->postInsert) {
            $this->assert(false, 'Entity postInsert event was not triggered.');
        }
    }
    
    public function updating()
    {
        $repo = new ContentRepository;
        
        try {
            $entity = $repo->update(new ContentEntity(array(
                'name' => 'Trey Shugart'
            )));
            $this->assert(false, 'Id requirement was not enforced when updating.');
        } catch (\Exception $e) {
            
        }
        
        try {
            $entity = $repo->insert(new ContentEntity(array(
                'id'   => 1,
                'name' => 'Trey Shugart'
            )));
            $entity = $repo->update($entity);
        } catch (\Exception $e) {
            $this->assert(false, 'Updating failed even though id was passed.');
        }
        
        if (!$entity->preSave) {
            $this->assert(false, 'Entity preSave event was not triggered.');
        }
        
        if (!$entity->postSave) {
            $this->assert(false, 'Entity postSave event was not triggered.');
        }
        
        if (!$entity->preUpdate) {
            $this->assert(false, 'Entity preUpdate event was not triggered.');
        }
        
        if (!$entity->postUpdate) {
            $this->assert(false, 'Entity postUpdate event was not triggered.');
        }
    }
    
    public function saving()
    {
        $repo = new ContentRepository;
        
        $entity = $repo->save(new ContentEntity(array(
            'name' => 'Trey Shugart'
        )));
        
        if (!$entity->preSave) {
            $this->assert(false, 'Entity preSave event was not triggered.');
        }
        
        if (!$entity->postSave) {
            $this->assert(false, 'Entity postSave event was not triggered.');
        }
        
        if (!$entity->preInsert) {
            $this->assert(false, 'Entity preInsert event was not triggered.');
        }
        
        if (!$entity->postInsert) {
            $this->assert(false, 'Entity postInsert event was not triggered.');
        }
        
        $entity = $repo->save(new ContentEntity($entity));
        
        if (!$entity->preUpdate) {
            $this->assert(false, 'Entity preUpdate event was not triggered.');
        }
        
        if (!$entity->postUpdate) {
            $this->assert(false, 'Entity postUpdate event was not triggered.');
        }
    }
    
    public function removing()
    {
        $repo   = new ContentRepository;
        $entity = new ContentEntity(1);
        
        if (!$entity->getId()) {
            $this->assert(false, 'Entity id was not set on constructor.');
        }
        
        $entity = $repo->remove($entity);
        
        if ($entity->getid()) {
            $this->assert(false, 'Entity id was not unset when removed.');
        }
        
        if (!$entity->preRemove) {
            $this->assert(false, 'Entity preRemove event was not triggered.');
        }
        
        if (!$entity->postRemove) {
            $this->assert(false, 'Entity postRemove event was not triggered.');
        }
    }
    
    public function caching()
    {
        $repo = new ContentRepository(new Cache);
        $item = new ContentEntity;
        $repo->save($item);
        
        $item = $repo->findById($item->id);
        if (!$item) {
            $this->assert(false, 'Item should have been found.');
        }
        
        $item = $repo->findById($item->id);
        if ($repo->findByIdCallCount > 1) {
            $this->assert(false, 'Method "ContentRepository->findById()" was called more than once so the cache did not find the item.');
        }
    }
}
<?php

use Model\EntitySet;
use Provider\CommentEntity;
use Provider\ContentEntity;
use Provider\UserEntity;

class Test_Entity extends Testes_UnitTest_Test
{
    public function constructorImporting()
    {
        $entity = new ContentEntity(1);
        $this->assert($entity->id, 'The id was not set.');
        
        $entity = new ContentEntity(array('id' => 1, 'name' => 'test'));
        $this->assert($entity->id && $entity->name, 'The id or name was not set.');
    }
    
    public function constructorEvents()
    {
        $content = new ContentEntity;
        $this->assert($content->preConstruct, 'Entity preConstruct was not triggered.');
        $this->assert($content->postConstruct, 'Entity postConstruct was not triggered.');
    }
    
    public function relationships()
    {
        $entity = new ContentEntity;
        $this->assert($entity->user instanceof UserEntity, 'User relationship was not instantiated.');
        $this->assert($entity->comments instanceof EntitySet, 'Comments relationship was not instantiated.');
    }
}
<?php

namespace Provider;
use Model\Entity;

class ContentEntity extends Entity
{
    public $preConstruct = false;
    
    public $postConstruct = false;
    
    public $preSave = false;
    
    public $postSave = false;
    
    public $preInsert = false;
    
    public $postInsert = false;
    
    public $preUpdate = false;
    
    public $postUpdate = false;
    
    public $preRemove = false;
    
    public $postRemove = false;
    
    public function preConstruct()
    {
        $this->hasOne('user', '\Provider\UserEntity');
        $this->hasMany('comments', '\Provider\CommentEntity');
        $this->preConstruct = true;
    }
    
    public function postConstruct()
    {
        $this->postConstruct = true;
    }
    
    public function preSave()
    {
        $this->preSave = true;
    }
    
    public function postSave()
    {
        $this->postSave = true;
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
    
    public function preRemove()
    {
        $this->preRemove = true;
    }
    
    public function postRemove()
    {
        $this->postRemove = true;
    }
}
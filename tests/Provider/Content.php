<?php

use Model\Entity;

class Provider_Content extends Entity
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
        $this->actAs(new Provider_Behavior_Default);
        $this->actAs(new Provider_Behavior_Content);
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
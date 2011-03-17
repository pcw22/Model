<?php

namespace Provider;
use Model\Entity;

class UserEntity extends Entity
{
    public function preConstruct()
    {
        $this->hasMany('content', '\Provider\ContentEntity');
    }
}
<?php

use Model\Entity;
use Model\Entity\Property;

class Provider_Friend extends Entity
{
    public function preConstruct()
    {
    	$this->set('user', new Property\HasOne('Provider_User'));
    	$this->set('status', new Property\Number(0, 10));
    }
}
<?php

class Provider_Friend extends Model_Entity
{
    public function preConstruct()
    {
    	$this->set('user', new Model_Entity_Property_HasOne('Provider_User'));
    	$this->set('status', new Model_Entity_Property_Number(0, 10));
    }
}
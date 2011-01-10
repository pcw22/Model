<?php

class Provider_Friend extends Model_Entity
{
    public function preConstruct()
    {
    	$this->set('user', new Model_Entity_Property_HasOne($entity, array('class' => 'Provider_User')));
    	$this->set('status', new Model_Entity_Property_Number($entity, array('min' => 0, 'max' => 10)));
    }
}
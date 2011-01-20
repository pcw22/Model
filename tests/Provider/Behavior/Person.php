<?php

class Provider_Behavior_Person implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->set('dob', new Model_Entity_Property_Date);
        $entity->set('created', new Model_Entity_Property_Date);
        $entity->set('updated', new Model_Entity_Property_Date);
        $entity->set('homepage', new Model_Entity_Property_HasOne('Provider_Content'));
        $entity->set('friends', new Model_Entity_Property_HasMany('Provider_User'));
    }
}
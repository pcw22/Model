<?php

class Provider_Behavior_Person implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->set('dob', new Model_Entity_Property_Date($entity));
        $entity->set('created', new Model_Entity_Property_Date($entity));
        $entity->set('updated', new Model_Entity_Property_Date($entity));
        $entity->set('homepage', new Model_Entity_Property_HasOne($entity, array('class' => 'Provider_Content')));
        $entity->set('friends', new Model_Entity_Property_HasMany($entity, array('class' => 'Provider_User')));
    }
}
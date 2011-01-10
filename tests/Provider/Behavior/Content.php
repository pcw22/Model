<?php

class Provider_Behavior_Content implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->set('user', new Model_Entity_Property_HasOne($entity, array('class' => 'Provider_User')));
        $entity->set('created', new Model_Entity_Property_Date($entity));
        $entity->set('updated', new Model_Entity_Property_Date($entity));
    }
}
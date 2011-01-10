<?php

class Provider_Behavior_Default implements Model_Entity_BehaviorInterface
{
    public function init(Model_Entity $entity)
    {
        $entity->alias('_id', 'id');
    }
}
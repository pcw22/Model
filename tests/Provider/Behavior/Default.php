<?php

use Model\Entity;

class Provider_Behavior_Default implements Entity\BehaviorInterface
{
    public function init(Entity $entity)
    {
        $entity->alias('_id', 'id');
    }
}
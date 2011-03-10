<?php

use Model\Entity;

class Provider_Behavior_Content implements Entity\BehaviorInterface
{
    public function init(Entity $entity)
    {
        $entity->set('user', new Entity\Property\HasOne('Provider_User'));
        $entity->set('created', new Entity\Property\Date);
        $entity->set('updated', new Entity\Property\Date);
    }
}
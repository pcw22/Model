<?php

use Model\Entity;
use Model\Entity\Property;

class Provider_Behavior_Person implements Entity\BehaviorInterface
{
    public function init(Entity $entity)
    {
        $entity->set('dob', new Property\Date);
        $entity->set('created', new Property\Date);
        $entity->set('updated', new Property\Date);
        $entity->set('homepage', new Property\HasOne('Provider_Content'));
        $entity->set('friends', new Property\HasMany('Provider_Friend'));
    }
}
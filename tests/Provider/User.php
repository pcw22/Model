<?php

use Model\Entity;

class Provider_User extends Entity
{
    public function preConstruct()
    {
        $this->actAs(new Provider_Behavior_Default);
        $this->actAs(new Provider_Behavior_Person);
    }
}
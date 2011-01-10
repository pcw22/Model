<?php

class Provider_User extends Model_Entity
{
    public function preConstruct()
    {
        $this->actAs(new Provider_Behavior_Default);
        $this->actAs(new Provider_Behavior_Person);
    }
}
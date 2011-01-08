<?php

class Model_Entity_Property_HasMany extends Model_Entity_Property_HasOne
{
    public function set($value)
    {
        // first check to make sure we even have a class specified
        $this->checkForClass();

        // then set the value in the set
        $this->value = new Model_EntitySet($this->data['class'], $value);
    }
}
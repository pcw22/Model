<?php

class Model_Entity_Property_Name extends Model_Entity_Property_Default
{
    public function set($name)
    {
        $names = explode(' ', $name);
        $names = array_filter($names, 'trim');

        if (isset($names[0])) {
            $this->entity->__set('forename', $names[0]);
        }

        if (isset($names[1])) {
            $this->entity->__set('surname', $names[1]);
        }
    }

    public function get()
    {
        return $this->entity->forename->get() . ' ' . $this->entity->surname->get();
    }
}
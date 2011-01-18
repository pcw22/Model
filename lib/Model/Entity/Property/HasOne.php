<?php

class Model_Entity_Property_HasOne extends Model_Entity_Property_Default
{
    public function set($value)
    {
        $this->checkForClass();

        // instantiate
        $class = $this->data['class'];
        $class = new $class($value);
        
        // make sure it's a valid instance
        if (!$class instanceof Model_Entity) {
            throw new Model_Exception(
                'The class "'
                . get_class($class)
                . '" must be a subclass of "Model_Entity".'
            );
        }

        // and set
        $this->value = $class;
    }
    
    public function get()
    {
        // then we can just return it
        return $this->value;
    }
    
    public function export()
    {
        // we only export if we have data to export
        if ($value = $this->get()) {
            return $value->export();
        }
        return array();
    }

    protected function checkForClass()
    {
        // make sure a proper class was set
        if (!isset($this->data['class'])) {
            throw new Model_Exception(
                'Cannot instantiate has-one relationship for "'
                . get_class($this->entity)
                . '" because "class" was not defined in the "data" array.'
            );
        }
    }
}
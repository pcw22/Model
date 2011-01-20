<?php

class Model_Entity_Property_Default implements Model_Entity_PropertyInterface
{
	protected $value = null;
    
    public function set($value)
    {
        $this->value = $value;
    }
    
    public function get()
    {
        return $this->value;
    }
    
    public function import($value)
    {
    	$this->set($value);
    }
    
    public function export()
    {
    	return $this->get();
    }
}
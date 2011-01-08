<?php

class Model_Entity_Property_Default implements Model_Entity_PropertyInterface
{
	protected $entity;

    protected $data = array();
	
	protected $value = null;

	public function __construct(Model_Entity $entity, array $data = array())
	{
		$this->entity = $entity;
        $this->data   = $this->data + $data;
        $this->init();
	}

    public function init()
    {
        
    }
    
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
<?php

interface Model_Entity_PropertyInterface
{
	public function __construct(Model_Entity $entity, array $data = array());
	
    public function set($value);
    
    public function get();

    public function import($value);

    public function export();
}
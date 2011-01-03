<?php

/**
 * The main driver interface. All model drivers must implement this.
 * 
 * @category Drivers
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Model_DriverInterface
{
    /**
     * We must provide driver specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param Model_Entity $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function insert(Model_Entity $entity);
    
    /**
     * We must provide driver specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param Model_Entity $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function update(Model_Entity $entity);
    
    /**
     * We must provide driver specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param Model_Entity $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function remove(Model_Entity $entity);
}
<?php

namespace Model;

/**
 * The main repository interface. All model repositorys must implement this.
 * 
 * @category Repositorys
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
interface RepositoryInterface
{
    /**
     * Returns an object by it's id.
     * 
     * @param mixed $id The id to find the item by.
     * 
     * @return mixed
     */
    public function findById($id);
    
    /**
     * We must provide repository specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function insert($entity);
    
    /**
     * We must provide repository specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function update($entity);
    
    /**
     * We must provide repository specific CRUD operations.
     * 
     * If this method is protected, events and the return value are automated.
     * If it is made public, then you are on your own to implement event
     * triggering, cancelation and return value.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function remove($entity);
}
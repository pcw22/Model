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
     * Returns an object by its id.
     * 
     * @param mixed $id The id to find the item by.
     * 
     * @return mixed
     */
    public function findById($id);
    
    /**
     * Inserts the specified entity and returns its id.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function insert($entity);
    
    /**
     * Updates the specified.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function update($entity);
    
    /**
     * Removes the specified entity.
     * 
     * @param \Model\Entity $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function remove($entity);
    
    /**
     * Returns the name of the entity class.
     * 
     * @return string
     */
    public function getEntityClassName();
}
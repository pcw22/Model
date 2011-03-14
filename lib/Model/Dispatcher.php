<?php

namespace Model;

/**
 * The main repository class. All model repositorys should extend this class.
 * 
 * @category Repositorys
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Dispatcher
{
    /**
     * The specific repository that the dispatcher is using.
     * 
     * @var \Model\RepositoryInterface
     */
    private $repository;
    
    /**
     * The cache repository, if any, to use for caching.
     * 
     * @var \Model\Cache\RepositoryInterface
     */
    private $cache;
    
    /**
     * Constructs a new dispatcher and sets the repository to use.
     * 
     * @param \Model\RepositoryInterface $repository The repository to use.
     * 
     * @return \Model\Dispatcher
     */
    public function __construct(RepositoryInterface $repository, CacheInterface $cache = null)
    {
        $this->repository = $repository;
        $this->cache      = $cache;
    }
    
    /**
     * Calls repository methods and automates caching.
     * 
     * @param string $name The repository method being called.
     * @param array  $args The arguments passed to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        if (!method_exists($this->repository, $name)) {
            throw new Exception(
                'Call to undefined repository method: '
                . get_class($this->repository)
                . '->'
                . $name
                . '.'
            );
        }
        
        // call the method and enforce a return type
        $value     = call_user_func_array(array($this->repository, $name), $args);
        $reflector = new MethodReflector($this->repository, $name);
        if (!$reflector->isValidReturnValue($value)) {
            throw new Exception(
                'The specified value was not a valid return type. Value: '
                . gettype($value)
                . '. Type(s): '
                . implode(', ', $reflector->getReturnTypes())
                . '.'
            );
        }
        
        return $value;
    }
    
    /**
     * Returns the repository instance that the dispatcher is using.
     * 
     * @return \Model\RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }
    
    /**
     * Acts as a proxy to automate cache insertion, updating and retrieval.
     * 
     * @param mixed $id The id to get the object by.
     * 
     * @return \Model\Entity
     */
    public function findById($id)
    {
        if ($item = $this->fromCache($id)) {
            return $item;
        }
        
        $item = $this->repository->findById($id);
        $item = $this->ensureEntity($item);
        $this->toCache($id, $item);
        return $item;
    }
    
    /**
     * Automates insert/update based on entity existence.
     * 
     * @param mixed $entity The entity being saved.
     * 
     * @return \Model\Repository
     */
    public function save($entity)
    {
        $entity = $this->ensureEntity($entity);
        if ($entity->exists()) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
        return $entity;
    }
    
    /**
     * Calls the implemented insert method and calls events. The insert method should return
     * an id to set on the entity. This ensure's that the entity will have an id when it is
     * inserted.
     * 
     * @param mixed $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function insert($entity)
    {
        $entity = $this->ensureEntity($entity);
        $entity->preSave();
        $entity->preInsert();
        
        $entity->id = $this->repository->insert($entity);
        $this->toCache($entity->id, $entity);
        
        $entity->postSave();
        $entity->postInsert();
        return $entity;
        
    }
    
    /**
     * Calls the implemented update method and calls events.
     * 
     * @param mixed $entity The entity to insert.
     * 
     * @return \Model\Repository
     */
    public function update($entity)
    {
        $entity = $this->ensureEntity($entity);
        $entity->preSave();
        $entity->preUpdate();
        
        $this->repository->update($entity);
        $this->toCache($entity->id, $entity);
        
        $entity->postSave();
        $entity->postUpdate();
        return $entity;
    }
    
    /**
     * Calls the implemented remove method and calls events.
     * 
     * @param \Model\Entity $entity The entity to remove.
     * 
     * @return \Model\Repository
     */
    public function remove($entity)
    {
        $entity = $this->ensureEntity($entity);
        $entity->preRemove();
        
        $this->repository->remove($entity);
        $this->removeCache($entity->id, $entity);
        
        $entity->postRemove();
        unset($entity->id);
        return $entity;
    }
    
    /**
     * Returns a new instance of the entity for the current repository.
     * 
     * @param mixed $values The entity or values to pass to the entity constructor.
     * 
     * @return \Model\Entity
     */
    private function ensureEntity($values = array())
    {
        $entity = $this->repository->getEntityClassName();
        
        // if the passed value is already a valid entity, just return it
        if ($values instanceof $entity) {
            return $values;
        }
        
        // reflect and make sure after reflecting that it's a valid subclass
        $entity = new \ReflectionClass($entity);
        if (!$entity->isSubclassOf('\Model\EntityAbstract')) {
            throw new Exception('The entity "' . $entity->getName() . '" must be a subclass of "\Model\EntityAbstract".');
        }
        return $entity->newInstance($values);
    }
    
    /**
     * Generates a cache key and returns it.
     * 
     * @param mixed $id The id of the item generate the key for.
     * 
     * @return string
     */
    private function generateCacheKey($id)
    {
        return md5($this->repository->getEntityClassName() . $id);
    }
    
    /**
     * Adds an item to the cache if a cache repository is set.
     * 
     * @param mixed $id   The id of the item to generate a key with.
     * @param mixed $item The item to cache.
     * 
     * @return \Model\Dispatcher
     */
    private function toCache($id, $item)
    {
        if ($this->cache) {
            $this->cache->set($this->generateCacheKey($id), $item);
        }
        return $this;
    }
    
    /**
     * Pulls an item from the cache if a cache repository is set.
     * 
     * @param mixed $id   The id of the item to generate a key with.
     * @param mixed $item The item to cache.
     * 
     * @return \Model\Dispatcher
     */
    private function fromCache($id)
    {
        if ($this->cache) {
            return $this->cache->get($this->generateCacheKey($id));
        }
        return false;
    }
    
    /**
     * Checks to see if an item exists in the cache if a cache repository is set.
     * 
     * @param mixed $id   The id of the item to generate a key with.
     * @param mixed $item The item to cache.
     * 
     * @return \Model\Dispatcher
     */
    private function hasCache($id)
    {
        if ($this->cache) {
            return $this->cache->exists($id);
        }
        return false;
    }
    
    /**
     * Removes an item from the cache if a cache repository is set.
     * 
     * @param mixed $id   The id of the item to generate a key with.
     * @param mixed $item The item to cache.
     * 
     * @return \Model\Dispatcher
     */
    private function removeCache($id)
    {
        if ($this->cache) {
            $this->cache->remove($this->generateCacheKey($id));
        }
        return $this;
    }
}
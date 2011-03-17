<?php

namespace Model;

/**
 * The main repository interface. All model repositorys must implement this.
 * 
 * @category Repositories
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
abstract class Repository
{
    /**
     * The cache driver to use, if any.
     * 
     * @var \Model\CacheInterface|null
     */
    private $cache;
    
    /**
     * Methods available to __call that must be implemented by the extending repository.
     * 
     * @var array
     */
    private $methods = array(
        'insert' => 'callInsert',
        'update' => 'callUpdate',
        'remove' => 'callRemove'
    );
    
    /**
     * Constructs a new repository with the specified cache driver.
     * 
     * @param \Model\CacheInterface $cache The cache drive to use, if any.
     * 
     * @return \Model\Repository
     */
    public function __construct(CacheInterface $cache = null)
    {
        $this->cache = $cache;
    }
    
    /**
     * Calls methods that should be implemented by the extending class. This is used instead of an
     * interface for limitations of PHP because we need the extending repositories to define specific
     * methods while having the flexibility to define their own parameter types for each. For example,
     * you can tell the interface you want an instance of \Model\Entity but the implementing class
     * cannot be more specific and type-hint a UserEntity that extends \Model\Entity. This allows it to.
     * 
     * Although calling "call_user_func_array()" inside of "__call()" is pretty bad for performance - to
     * say the least - it won't affect scaling as repository methods shouldn't be called tens-of-thousands
     * of times and re-architecting it would be a micro-optimization and trade-off maintainability.
     * 
     * @param string $name The name of the method to call.
     * @param array  $args The arguments to pass to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        if (!isset($this->methods[$name])) {
            throw new Exception('Call to undefined method "' . get_class($this) . '->' . $name . '()".');
        }
        
        $method = $this->methods[$name];
        if (!method_exists($this, $method)) {
            throw new Exception('Class "' . get_class($this) . '" must implement method "' . $name . '()".');
        }
        
        // methods are abstracted using the prefix "call"
        return call_user_func_array(array($this, $method), $args);
    }
    
    public function save(Entity $entity)
    {
        if ($entity->hasId()) {
            $entity = $this->callUpdate($entity);
        } else {
            $entity = $this->callInsert($entity);
        }
        
        return $entity;
    }
    
    private function callInsert(Entity $entity)
    {
        $entity->preSave();
        $entity->preInsert();
        $this->insert($entity);
        $entity->postInsert();
        $entity->postSave();
        
        if (!$entity->hasId()) {
            throw new Exception('Entity "' . get_class($entity) . '" must contain an id after being inserted.');
        }
        
        return $entity;
    }
    
    private function callUpdate(Entity $entity)
    {
        if (!$entity->hasId()) {
            throw new Exception('Entity "' . get_class($entity) . '" must contain an id when being updated.');
        }
        
        $entity->preSave();
        $entity->preUpdate();
        $this->update($entity);
        $entity->postUpdate();
        $entity->postSave();
        
        return $entity;
    }
    
    private function callRemove(Entity $entity)
    {
        if (!$entity->hasId()) {
            throw new Exception('Entity "' . get_class($entity) . '" must contain an id before being removed.');
        }
        
        $entity->preRemove();
        $this->remove($entity);
        $entity->removeId();
        $entity->postRemove();
        
        return $entity;
    }
    
    /**
     * Provides a way to cache a method other than the one that was called.
     * 
     * @param string $method
     * @param array  $args
     * @param mixed  $item
     * @param mixed  $time
     * 
     * @return \Model\Repository
     */
    protected function persist($method, array $args, $item, $time = null)
    {
        if ($this->cache) {
            $this->cache->set($this->generateCacheKey($method, $args), $item, $time);
        }
        return $this;
    }
    
    /**
     * Provides a way to cache a method other than the one that was called.
     * 
     * @param string $method
     * @param array  $args
     * 
     * @return \Model\Repository
     */
    protected function retrieve($method, array $args)
    {
        if ($this->cache) {
            return $this->cache->get($this->generateCacheKey($method, $args));
        }
        return false;
    }
    
    /**
     * Provides a way to expire a method cache other than the one that was called.
     * 
     * @param string $method
     * @param array  $args
     * 
     * @return \Model\Repository
     */
    protected function expire($method, array $args)
    {
        if ($this->cache) {
            $this->cache->remove($this->generateCacheKey($method, $args));
        }
        return $this;
    }
    
    /**
     * Generates a cache key for the specified method and arguments.
     * 
     * @param string $method The method to generate the key for.
     * @param array  $args   The arguments passed to the method.
     * 
     * @return string
     */
    private function generateCacheKey($method, array $args)
    {
        return md5(get_class($this) . $method . serialize($args));
    }
}
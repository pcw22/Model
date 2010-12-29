<?php

/**
 * The main driver class. All model drivers should extend this class.
 * 
 * @category Drivers
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Model_Driver
{
    /**
     * The cache driver, if any, to use for caching.
     * 
     * @var Model_Cache_DriverInterface
     */
    protected $cache = null;
    
    /**
     * The cache strategy, if any, to use for encoding the cache value.
     * 
     * @var Model_Cache_StrategyInterface
     */
    protected $strategy = null;
    
    /**
     * Returns the specified entity by its id.
     * 
     * @param mixed $id The id to find the entity by.
     * 
     * @return mixed
     */
    abstract public function findById($id);
    
    /**
     * Saves the passed in entity.
     * 
     * @param Model_Entity $entity The model entity to save.
     * 
     * @return void
     */
    abstract public function save(Model_Entity $entity);
    
    /**
     * If an driver method is defined as anything but public, it goes through
     * __call which will automate caching if available.
     * 
     * @param string $name The driver method being called.
     * @param array  $args The arguments passed to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        // first check to see if we have a method
        if (!method_exists($this, $name)) {
            throw new Model_Exception(
                'Call to undefined driver method: '
                . get_class($this)
                . '->'
                . $name
                . '.'
            );
        }
        
        // generate a cache key for storing and retrieving id sets
        $key = md5(get_class($this) . $name . serialize($args));
        
        // attempt to retrieve the cached ids and load them
        if ($this->cache) {
            if ($ids = $this->cache->get('ids' . $key)) {
                return $this->_findByIds($ids);
            } elseif ($result = $this->cache->get('result' . $key)) {
                return $result;
            }
        }
        
        // otherwise, get the result from the method
        $value = call_user_func_array(array($this, $name), $args);
        
        // attempt to cache the value using the key
        if ($this->cache) {
            if ($value instanceof Model_Entity) {
                $this->cache->set(get_class($this) . $value->__get(Model_Entity::ID));
            } elseif ($value instanceof Model_EntitySet) {
                // cache the id array
                $this->cache->set('ids' . $key, $value->aggregate(Model_Entity::ID));
                
                // now cache each value
                foreach ($value as $entity) {
                    $this->cache->set(get_class($this) . $entity->__get(Model_Entity::ID));
                }
            } else {
                $this->cache->set('result' . $key, $value);
            }
        }
        
        // pass on the result
        return $value;
    }
    
    /**
     * Sets the cache driver to use.
     * 
     * @param Model_Cache_DriverInterface $driver The driver to handle the cache.
     * 
     * @return Model_Driver
     */
    public function setCache(Model_Cache_DriverInterface $driver = null)
    {
        $this->cache = $driver;
        return $this;
    }
    
    /**
     * Finds the items by their id.
     * 
     * @param array $ids The ids to find.
     * 
     * @return Model_EntitySet
     */
    private function _findByIds(array $ids)
    {
        $set = new Model_EntitySet(get_class($this));
        foreach ($ids as $id) {
            $key = get_class($this) . $id;
            if ($entity = $this->_fromCache($key)) {
                $set[] = $entity;
            } elseif ($entity) {
                $set[] = $this->findById($id);
            }
        }
        return $set;
    }
}
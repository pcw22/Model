<?php

/**
 * The main driver class. All model drivers should extend this class.
 * 
 * @category Drivers
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Dispatcher
{
    /**
     * The specific driver that the dispatcher is using.
     * 
     * @var Model_DriverInterface
     */
    private $_driver;
    
    /**
     * The cache driver, if any, to use for caching.
     * 
     * @var Model_Cache_DriverInterface
     */
    private $_cache;
    
    /**
     * Constructs a new dispatcher and sets the driver to use.
     * 
     * @param Model_DriverInterface $driver The driver to use.
     * 
     * @return Model_Dispatcher
     */
    public function __construct(Model_DriverInterface $driver, Model_CacheInterface $cache = null)
    {
        $this->_driver = $driver;
        $this->_cache  = $cache;
    }
    
    /**
     * Calls driver methods and automates caching.
     * 
     * @param string $name The driver method being called.
     * @param array  $args The arguments passed to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        // handle other methods
        if (method_exists($this->_driver, $name)) {
            return $this->_call($name, $args);
        }
        
        // method doesn't exist
        throw new Model_Exception(
            'Call to undefined driver method: '
            . get_class($this->_driver)
            . '->'
            . $name
            . '.'
        );
    }
    
    /**
     * Returns the driver instance that the dispatcher is using.
     * 
     * @return Model_DriverInterface
     */
    public function getDriver()
    {
        return $this->_driver;
    }
    
    /**
     * Automates insert/update based on entity existence.
     * 
     * @param Model_Entity $entity The entity being saved.
     * 
     * @return Model_Driver
     */
    public function save(Model_Entity $entity)
    {
        // if an id is set, update, if not, insert
        if ($entity->exists()) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
        
        // chain
        return $this;
    }
    
    /**
     * Calls the implemented insert method and calls events.
     * 
     * @param Model_Entity $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function insert(Model_Entity $entity)
    {
        // ensure validity
        if ($entity->validate() === false) {
            throw new Model_Exception('The entity "' . get_class($entity) . '" was unable to be inserted.');
        }
        
        // pre-save events
        $entity->preSave();
        $entity->preUpdate();
        
        // call driver method
        $this->_driver->insert($entity);
        
        // post-save events
        $entity->postSave();
        $entity->postInsert();
        
        // chain
        return $this;
        
    }
    
    /**
     * Calls the implemented update method and calls events.
     * 
     * @param Model_Entity $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function update(Model_Entity $entity)
    {
        // ensure validity
        if ($entity->validate() === false) {
            throw new Model_Exception('The entity "' . get_class($entity) . '" was unable to be updated.');
        }
        
        // pre-save events
        $entity->preSave();
        $entity->preUpdate();
        
        // call driver method
        $this->_driver->update($entity);
        
        // post-save events
        $entity->postSave();
        $entity->postUpdate();
        
        // chain
        return $this;
    }
    
    /**
     * Calls the implemented remove method and calls events.
     * 
     * @param Model_Entity $entity The entity to remove.
     * 
     * @return Model_Driver
     */
    public function remove(Model_Entity $entity)
    {
        if ($entity->preRemove() === false) {
            return $this;
        }
        $this->_driver->remove($entity);
        $entity->postRemove();
        return $this;
    }
    
    /**
     * Calls any other methods that aren't public and automates caching.
     * 
     * @param string $name The method name.
     * @param array  $args The method arguments.
     * 
     * @return mixed
     */
    private function _call($name, $args)
    {
        // generate a cache key for storing and retrieving id sets
        $key = md5(get_class($this->_driver) . $name . serialize($args));
        
        // attempt to retrieve the cached ids and load them
        if ($value = $this->_fromCache($key)) {
            return $value;
        }
        
        // otherwise, get the result from the method
        $value = call_user_func_array(array($this->_driver, $name), $args);
        
        // attempt to cache the value using the key
        $this->_toCache($key, $value);
        
        // pass on the result
        return $value;
    }
    
    /**
     * Adds an item to the cache.
     * 
     * @param string $key The cache key.
     * 
     * @return mixed
     */
    private function _fromCache($key)
    {
        if ($this->_cache) {
            return $this->_cache->get($key);
        }
        return null;
    }
    
    /**
     * Puts an item in the cache.
     * 
     * @param string $key   The cache key.
     * @param mixed  $value The value.
     * 
     * @return Model_Driver
     */
    private function _toCache($key, $value)
    {
        if ($this->_cache) {
            $this->_cache->set($key, $value);
        }
        return $this;
    }
}
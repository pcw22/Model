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
     * The entity name to use.
     * 
     * @var string
     */
    private $_entity;
    
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
    public function __construct(Model_DriverInterface $driver, $entity, Model_CacheInterface $cache = null)
    {
        $this->_driver = $driver;
        $this->_entity = $entity;
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
     * @param mixed $entity The entity being saved.
     * 
     * @return Model_Driver
     */
    public function save($entity)
    {
        // ensure an entity is used
        $entity = $this->_ensureEntity($entity);
        
        // if an id is set, update, if not, insert
        if ($entity->exists()) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
        
        // return the entity
        return $entity;
    }
    
    /**
     * Calls the implemented insert method and calls events.
     * 
     * @param mixed $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function insert($entity)
    {
        // ensure an entity is used
        $entity = $this->_ensureEntity($entity);
        
        // ensure validity
        if ($entity->preSave() === false || $entity->preInsert() === false) {
            throw new Model_Exception('The entity "' . get_class($entity) . '" was unable to be updated because it is not valid.');
        }
        
        // call driver method
        $this->_driver->insert($entity);
        
        // post-save events
        $entity->postSave();
        $entity->postInsert();
        
        // return the entity
        return $entity;
        
    }
    
    /**
     * Calls the implemented update method and calls events.
     * 
     * @param mixed $entity The entity to insert.
     * 
     * @return Model_Driver
     */
    public function update($entity)
    {
        // ensure an entity is used
        $entity = $this->_ensureEntity($entity);
        
        // ensure validity
        if ($entity->preSave() === false || $entity->preUpdate() === false) {
            throw new Model_Exception('The entity "' . get_class($entity) . '" was unable to be updated because it is not valid.');
        }
        
        // call driver method
        $this->_driver->update($entity);
        
        // post-save events
        $entity->postSave();
        $entity->postUpdate();
        
        // return the entity
        return $entity;
    }
    
    /**
     * Calls the implemented remove method and calls events.
     * 
     * @param Model_Entity $entity The entity to remove.
     * 
     * @return Model_Driver
     */
    public function remove($entity)
    {
        // ensure an entity is used
        $entity = $this->_ensureEntity($entity);
        
        // cancel removing if
        if ($entity->preRemove() === false) {
            throw new Model_Exception('The entity "' . get_class($entity) . '" was unable to be updated because it is not valid.');
        }
        
        // remove
        $this->_driver->remove($entity);
        
        // post-remove event
        $entity->postRemove();
        
        // return the entity
        return $entity;
    }
    
    /**
     * Returns a new instance of the entity for the current driver.
     * 
     * @param mixed $values The entity or values to pass to the entity constructor.
     * 
     * @return Model_Entity
     */
    public function _ensureEntity($values = array())
    {
        // if the passed value is already a valid entity, just return it
        if ($values instanceof $this->_entity) {
            return $values;
        }
        
        // reflect the entity class
        $entity = new ReflectionClass($this->_entity);
        
        // make sure after reflecting that it's a valid subclass
        if (!$entity->isSubclassOf('Model_Entity')) {
            throw new Model_Exception('The entity "' . $entity->getName() . '" must be a subclass of "Moden_Entity".');
        }
        
        // return a new instance of it and pass it the passed values
        return $entity->newInstance($values);
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
Introduction
------------

### What

Model - pardon the lack of imagination - is a simple, easy-to-use enitity/model framework that utilizes custom drivers for back-end integration.

### Why

Model allows a common interface to be used across multiple drivers that interact with the storage mechanism. This is NOT abstracted because many projects that do complete abstraction, cannot cover the level of detail one must undertake on a driver-specific level in order to ensure each backend is being used properly and will actually scale.

Separating drivers that implement a common interface ensures maintainability while giving the programmer full control over how their backends are accessed.

### How

Read and find out.

Theory of Operation with a Sample Driver
----------------------------------------

Say we want to use both MongoDb and Mysql as backends. However, these two databases are completely different in almost every respect. So how can we access these through a common interface? Keep reading.

To start off, we define a common interface for all drivers. For example, a driver for a content item in a CMS:

    <?php
    
    interface ContentInterface extends Model_DriverInterface
    {
        public function find(array $params);
        
        public function logIn($username, $password);
        
        public function logOut($id);
    }

The parent interface `Model_DriverInterface` ensures certain required methods are defined within each driver.

All content drivers can now implement this interface to make sure that the data can be accessed in exactly the same way for each one.

    <?php
    
    class Mongo_Content implements ContentInterface
    {
        public function insert($data)
        {
        
        }
        
        public function update($data)
        {
        
        }
        
        public function remove($data)
        {
        
        }
        
        public function find(array $params)
        {
        
        }
        
        public function logIn($username, $password)
        {
        
        }
        
        public function logOut($id)
        {
        
        }
    }

Configuration
-------------

    array(
        'driver'       => null,
        'driver.class' => ':driver_:name',
        'entity.class' => ':name',
        'cache.class'  => null,
        'cache.args'   => array()
    )

### driver

The driver configuration variable specifies which custom driver the dispatcher should use. This will be used to format which driver class should be instantiated.

### driver.class

The driver class name to instantiate and pass to the dispatcher. Two variables are allowed to be used in the `driver.class` variable.

#### `:driver`

The `:driver` format variable is replaced with a `CamelCapped` version of the `driver` configuration variable. For example, setting the driver as `mongodb`, it would be formatted as `Mongodb`.

#### `:name`

The `:name` format variable refers to the property name that was accessed via the `Model` instance. It is `CamelCapped` when replaced. For example, `content` would be replaced with `Content`.

### entity.class

The entity class name to pass to the dispatcher. It accepts a single format variable.

#### `:name`

Refers to the property name that was acdessed via the `Model` instance. It is `CamelCapped` when replaced. For example, `content` would be replaced with `Content`.

### cache.class

The classname of the cache driver to use for automated caching. This varaible is formatted in exactly the same way as the `driver.class` configuration varaible if it is specified.

If a caching class is not specified, no caching takes place.

### cache.args

An array of arguments to pass to the cache driver constructor. The arguments set here will depend upon which cache driver you are using. Cache drivers are explained later on.

The Main Model Container
------------------------

The main model container class, `Model`, acts as a dependency-injection container automating the instantiation and configuration of dispatchers and drivers.

### Constructing

    $model = new Model;

### Setting Default Configuration

The default configuration is the configuration that is merged with whatever is passed to the `Model::__construct()` method.

    // set a default driver
    Model::setDefaultConfig(array('driver' => 'mysql');
    
    // create a new model container that uses the 'mysql' driver
    $model = new Model;

We can also access the default configuration:

    $defaultConfig = Model::getDefaultConfig();

### Caching Instances

    // cache a named instance as 'myInstance'
    Model::set('myInstance', new Model);
    
Now we can access that cached instance:

    $model = Model::get('myInstance');

### Setting a Default Instance

We can set a default instance to use if no instance name is passed to `Model::get()`. However, if no default instance is specified, one by the name of 'default' is created.

    // we can use the 'default' instance
    $default = Model::get();
    
    // both check for 'default'
    Model::has(); // true
    Model::has('default'); // true
    
    // both remove 'default'
    Model::remove();
    Model::remove('default');
    
    // or we can specify one
    Model::setDefault('myInstance');
    
    // and use it as the defalut
    $myInstance = Model::get();
    
    // both check for 'myInstance'
    Model::has(); // true
    Model::has('myInstance'); // true
    
    // both remove 'myInstance'
    Model::remove();
    Model::remove('myInstance');

Once set we can check to see if the instance exists:

    Model::has('myInstance'); // true

and then remove it if we don't want it anymore:

    Model::remove('myInstance');
    Model::has('myInstance'); // false

The Model Dispatcher
--------------------

The dispatcher acts as an intermediary driver. Its instatiation and configuration is automated by the `Model` class, however it can be used on its own.

    $dispatcher = new Model_Dispatcher(
        new Mongo_Content,
        'Content',
        Model_Cache_Memcache
    );

When we call a method on it:

    $dispatcher->find();

It calls the actual model driver method: `Mongo_Content->find()`.
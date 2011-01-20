Introduction
------------

### What

Model - pardon the lack of imagination - is a simple, easy-to-use enitity/model framework that utilizes custom drivers for back-end integration. Its goal is to facilitate Domain Driven Development and automate monotonous tasks while not trying to get in the way by doing everything for you.

### Why

Model allows a common interface to be used across multiple drivers that interact with the storage mechanism. This is NOT abstracted because many projects that do complete abstraction, cannot cover the level of detail one must undertake on a driver-specific level in order to ensure each backend is being used properly and will actually scale.

Separating drivers that implement a common interface ensures maintainability while giving the programmer full control over how their backends are accessed.

### How

Read and find out.

Theory of Abstraction with a Sample Driver
----------------------------------------

Say we want to use both MongoDb and Mysql as backends. These two databases are completely different in almost every respect. So how can we access these through a common interface?

To start off, we define a common interface for all drivers. For example, a driver for a user:

    <?php
    
    interface UserRepository extends Model_DriverInterface
    {
        /**
         * @return Model_EntitySet
         */
        public function find(array $params);
        
        /**
         * @return User
         */
        public function logIn($username, $password);
        
        /**
         * @return bool
         */
        public function logOut($id);
    }

The parent interface `Model_DriverInterface` ensures certain required methods are defined within each driver. Each driver *must* implement 3 methods: `insert()`, `update()` and `remove()`.

All content drivers can now implement this interface to make sure that the data can be accessed in exactly the same way for each one.

Mongo driver:

    <?php
    
    class Mongo_User implements UserRepository
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

Mysql driver:

    <?php
    
    class Mysql_User implements UserRepository
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

The default configuration:

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
    Model::setDefaultConfig(array('driver' => 'mysql'));
    
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

The Model Dispatcher
--------------------

The dispatcher acts as an intermediary driver. Its instantiation and configuration is automated by the `Model` class, however it can be used on its own.

    $dispatcher = new Model_Dispatcher(
        new Mongo_User,
        'User',
        new Model_Cache_Static
    );

When we call a method on it:

    $dispatcher->find();

It calls the actual model driver method: `Mongo_Content->find()`.

The dispatcher provides a gateway to all driver methods and allows transparent backend switching. It also provides access to a `save()` method which automates whether or `insert()` or `update()` is called depending on if an `_id` property is set.

Entities
--------

Your entities provide a common object for that all drivers can use and translate to their respective backends. To create an entity, all you need to do is extend the base `Model_Entity` class which gives you a set of defaults.

    <?php
    
    class User extends Model_Entity
    {
        
    }

You can now modify your entity and save it to any number of backends:

    <?php
    
    $user        = new User;
    $user->name  = 'The Dude',
    $user->email = 'yourdudeness@duder.net'
    
    Model::get()->user->save($user);

Note, that when inserting, updating, saving and removing, the entity is returned from the dispatching method.

    $user = Model::get()->user->save($user);
    $user->exists(); // true

This allows you to do any necessary modifications to the object upon save. You may want to set an `_id` after the item is inserted so when you `save()` again, the item is updated, for example.

Also, for the sake of simplicity, you can pass any value to any of the dispatcher CRUD operations.

    $user = Model::get()->user->save(
        array(
            'name'  => 'The Dude',
            'email' => 'yourdudeness@duder.net'
        )
    );
    $user->exists(); // true

You can also pass nearly any argument to the constructor of an entity.

    <?php
    
    // an id
    $user = new User(1);
    $user->_id; // 1
    
    // an array
    $user = new User(array('name' => 'The Dude'));
    $user->name; // "The Dude"
    
    // an object
    $user = new User($user);
    $user->name; // "The Dude"

Entity Ids
----------

By default, an entity uses a primary key called `_id`. If you want to use a different one, all you have to do is alias it:

    $user->alias('_id', 'id');

This won't change the column name on the database, but how it is used when interacted with on the entity:

    $user->id = 1;
    $user->id === $user->_id;

Entity Sets
-----------

Entity sets allow you to use a grouping of a certain type of entities as an array-like object. You can access it like an array, iterate over it and even count the items inside of it.

    <?php
    
    $set = new Model_EntitySet(
        'User',
        array(
            array(
                'name'  => 'The Dude1',
                'email' => 'yourdudeness1@duder.net'
            ),
            array(
                'name'  => 'The Dude2',
                'email' => 'yourdudeness2@duder.net'
            )
        )
    );

The second argument to `Model_EntitySet` can be anything that can be passed to the constructor of an entity. Even if it is an integer or string, it will still figure it out.

    <?php
    
    $set = new Model_EntitySet('User', 1);
    $set[0]->_id; // 1

Entity Properties
-----------------

Each property on an entity is an instance of `Model_Entity_PropertyInterface`. This may seem a bit much at first, but one must consider maintainability when needing properties to behave in certain ways. By default, a property is instantiated as a `Model_Entity_Property_Default`.

    <?php
    
    $user = new User;
    $user->get('name'); // Model_Entity_Property_Default
    
We can set this to a different property if we want.

    $user->set('name', new MyCustomProperty);

When we call `__get()` or `__set()` on an entity, it implicitly calls `get()` and `set()` recpectively on the specified property object.

    $user->name === $user->get('name')->get();

Properties also have `import()` and `export()` methods which are called rather than `get()` and `set()` when `import()` or `export()` is called on a given entity.

Given:

    $data = array('name' => 'The Dude');

The following are the same:

    $user->import($data);
    $user->get('name')->import($data['name']);

Same with exporting:

    $data = $user->export();
    $data['name'] === $user->get('name')->export();

Entity Behaviors
----------------

Behaviors are sets of macros that are intended to setup entities. Say you have many different items in your system that all require the same fields: created and updated.

You can set up a behavior to automate this:

    <?php
    
    class Timestampable implements Model_Entity_BehaviorInterface
    {
        public function init(Model_Entity $entity)
        {
            $entity->set('created', new Model_Entity_Property_Date($entity));
            $entity->set('updated', new Model_Entity_Property_Date($entity));
        }
    }

And easily apply the behavior to the entity:

    <?php
    
    $user->actAs(new Timestampable);

Entity Events
-------------

There are a number of event methods called on an entity depending on what state the entity is in or how it is being manipulated.

### preConstruct

Called during `__construct()`, but before anything happens to the entity.

### postConstruct

Called during `__construct()`, but only after it has been set up and data imported.

### preSave

Called prior to the more specific `preInsert` and `preUpdate`. Allows the you to return false in order to cancel saving. If saving is cancelled, an exception is thrown.

### postSave

Called after inserting or updating but proir to the more spedific `postInsert` and `postUpdate`.

### preInsert

Called prior to inserting. Allows you to return false in order to cancel saving. If saving is cancelled, an exception is thrown. Alternatively, you can throw an exception yourself.

### postInsert

Called after insertion.

### preUpdate

Called prior to updating. Allows you to return false in order to cancel saving. If saving is cancelled, an exception is thrown. Alternatively, you can throw an exception yourself.

### postUpdate

Called after updating.

### preRemove

Called prior to removing. Allows you to return false in order to cancel saving. If saving is cancelled, an exception is thrown. Alternatively, you can throw an exception yourself.

### postRemove

Called after removing.
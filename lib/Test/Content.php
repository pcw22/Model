<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL ^ E_STRICT);

require dirname(__FILE__) . '/../Model/Autoloader.php';
Model_Autoloader::register();

class Content extends Model_Entity
{
    
}

class Content_Mysql extends Model_Adapter
{
    public function save(Model_Entity $entity)
    {
        
    }
}

class Content_Mssql extends Model_Adapter
{
    public function save(Model_Entity $entity)
    {
        die(var_dump($entity));
    }
}

// set up the content model
Model::getInstance(array('adapter' => 'Mssql'));

// set via constructor
$content = new Content(
    array(
        'id' => 1
    )
);

// set via properties
$content->title = 'my test title';

// set via method
$content->set('body', 'my test body');

// set via setter
$content->setAuthor('tres');

// save through the adapter
Model::getInstance()->content->save($content);

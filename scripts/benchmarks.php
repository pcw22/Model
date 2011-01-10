<?php

error_reporting(E_ALL ^ E_STRICT);

require dirname(__FILE__) . '/../lib/Model/Autoloader.php';
require dirname(__FILE__) . '/../lib/Testes/Autoloader.php';
Model_Autoloader::register();
Testes_Autoloader::register(dirname(__FILE__) . '/../tests');

$bench = new Bench;
echo $bench->run();
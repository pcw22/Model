<?php

require dirname(__FILE__) . '/../lib/Model/Autoloader.php';
require dirname(__FILE__) . '/../lib/Testes/Autoloader.php';
Model_Autoloader::register();
Testes_Autoloader::register(dirname(__FILE__) . '/../lib');

$tests = new Test_Model;
$tests->run();

if ($assertions = $tests->assertions()) {
    echo "Tests failed:\n";
    foreach ($assertions as $assertion) {
        echo '  '
           , $assertion->getTestFile()
           , '('
           , $assertion->getTestLine()
           , '): '
           , $assertion->getMessage()
           , '. In: '
           , $assertion->getTestClass()
           , '->'
           , $assertion->getTestMethod()
           , "\n";
    }
} else {
    echo "Tests passed!\n";
}
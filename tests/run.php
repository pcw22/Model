<?php

require dirname(__FILE__) . '/../lib/Model/Autoloader.php';
require dirname(__FILE__) . '/../lib/Testes/Autoloader.php';
Model_Autoloader::register();
Testes_Autoloader::register();

$test = new Testes_Suite(dirname(__FILE__) . '/../lib/Model/Test', 'Model_Test');
$test->run();

if ($count = count($test->incomplete())) {
    echo "{$count} incomplete\n";
    foreach ($test->incomplete() as $failed) {
        echo "  {$failed}\n";
    }
}

if ($count = count($test->failed())) {
    echo "{$count} failed\n";
    foreach ($test->failed() as $incomplete) {
        echo "  {$incomplete}\n";
    }
}

echo "{$test->count()} in total\n";
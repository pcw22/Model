<?php

require dirname(__FILE__) . '/../lib/Model/Autoloader.php';
require dirname(__FILE__) . '/../lib/Testes/Autoloader.php';
Model_Autoloader::register();
Testes_Autoloader::register();

$test = new Testes_Suite(dirname(__FILE__) . '/../lib/Model/Test', 'Model_Test');
$test->run();

// output passed tests
echo count($test->passed()) . " passed\n";

// output incomplete tests if they exist
if ($count = count($test->incomplete())) {
    echo "{$count} incomplete\n";
    foreach ($test->incomplete() as $failed) {
        echo "  {$failed}\n";
    }
}

// output failed tests if they exist
if ($count = count($test->failed())) {
    echo "{$count} failed\n";
    foreach ($test->failed() as $incomplete) {
        echo "  {$incomplete}\n";
    }
}

// output the total number of tests run
if ($test->count() === 1) {
    echo '1 test';
} else {
    echo "{$test->count()} tests";
}
echo " in total\n";
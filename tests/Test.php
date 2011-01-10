<?php

class Test extends Testes_UnitTest
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'driver'       => 'Mock',
                'driver.class' => 'Provider_:driver_:name',
                'entity.class' => 'Provider_:name',
                'cache.class'  => 'Model_Cache_Static'
            )
        );
    }
}
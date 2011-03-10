<?php

class Test extends Testes_UnitTest
{
    public function setUp()
    {
        Model::setDefaultConfig(
            array(
                'repository.class' => 'Provider_Mock_:name',
                'entity.class'     => 'Provider_:name',
                'cache.class'      => '\Model\Cache\Php'
            )
        );
    }
}
<?php

/**
 * Outlines what a behavior must implement.
 * 
 * @category Behaviors
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
interface Model_Entity_BehaviorInterface
{
    /**
     * Initializes the passed entity to behave a certain way.
     * 
     * @param Model_Entity $entity The entity to initialize.
     * 
     * @return void
     */
	public function init(Model_Entity $entity);
}
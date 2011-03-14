<?php

namespace Model\Entity;
use Model\EntityAbstract;

/**
 * Outlines what a behavior must implement.
 * 
 * @category Behaviors
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
interface BehaviorInterface
{
    /**
     * Initializes the passed entity to behave a certain way.
     * 
     * @param \Model\EntityAbstract $entity The entity to initialize.
     * 
     * @return void
     */
	public function init(EntityAbstract $entity);
}
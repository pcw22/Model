<?php

interface Provider_ContentInterface extends \Model\RepositoryInterface
{
    /**
     * Docblock that may return anything. Tests specifying "mixed".
     * 
     * @param int $id The id to find the content item by.
     * 
     * @return mixed
     */
    public function findByIdNoType($id);
}
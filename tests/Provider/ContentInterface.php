<?php

interface Provider_ContentInterface extends Model_DriverInterface
{
    /**
     * Providing docblocks can make the return value of each model method
     * strongly typed.
     * 
     * @param int $id The id to find the content item by.
     * 
     * @return Provider_Content
     */
    public function findById($id);
    
    /**
     * Docblock that may return anything. Tests specifying "mixed".
     * 
     * @param int $id The id to find the content item by.
     * 
     * @return mixed
     */
    public function findByIdNoType($id);
}
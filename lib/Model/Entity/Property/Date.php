<?php

/**
 * A date property for manipulating date fields.
 * 
 * @category Properties
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Entity_Property_Date extends Model_Entity_Property_Default
{
    /**
     * The date format.
     * 
     * @var string
     */
    protected $format;
    
    /**
     * The timezone to use.
     * 
     * @var string
     */
    protected $timezone;

    /**
     * Constructs a new date property and sets a default format and timezone.
     * 
     * @param
     * 
     * @return Model_Entity_Property_Date
     */
    public function __construct($format = 'Y-m-d H:i:s', $timezone = 'GMT')
    {
        $this->format   = $format;
        $this->timezone = $timezone;
        $this->value    = new DateTime('now', new DateTimeZone($this->timezone));
    }
    
    /**
     * Sets the date.
     * 
     * @param string $date The date to set.
     * 
     * @return void
     */
    public function set($date)
    {
        $this->value = new DateTime($date, new DateTimeZone($this->timezone));
    }

    /**
     * Returns the date using the specified format.
     * 
     * @return string
     */
    public function get()
    {
        return $this->value->format($this->format);
    }
}
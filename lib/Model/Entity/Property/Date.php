<?php

class Model_Entity_Property_Date extends Model_Entity_Property_Default
{
    protected $format;

    protected $timezone;

    public static $defaultFormat = 'Y-m-d H:i:s';

    public static $defaultTimezone = 'GMT';

    public function __construct()
    {
        $this->format   = self::$defaultFormat;
        $this->timezone = self::$defaultTimezone;
        $this->value    = new DateTime('now', new DateTimeZone($this->timezone));
    }

    public function set($date)
    {
        $this->value = new DateTime($date, new DateTimeZone($this->timezone));
    }

    public function get()
    {
        return $this->value->format($this->format);
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setTimezone($timezone)
    {
        if ($this->value) {
            $this->value->setTimezone(new DateTimeZone($timezone));
        }
        $this->timezone = $timezone;
        return $this;
    }

    public function getTimezone($timezone)
    {
        return $this->timezone;
    }

    public static function setDefaultFormat($format)
    {
        self::$defaultFormat = $format;
    }

    public static function getDefaultFormat()
    {
        return self::$defaultFormat;
    }

    public static function setDefaultTimezone($timezone)
    {
        self::$defaultTimezone = $timezone;
    }

    public static function getDefaultTimezone()
    {
        return self::$defaultTimezone;
    }
}
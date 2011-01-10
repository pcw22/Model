<?php

class Model_Entity_Property_Number extends Model_Entity_Property_Default
{
    protected $data = array(
        'min' => null,
        'max' => null
    );

    protected $value = 0;

    public function set($name)
    {
        $number = (int) $number;
        if (is_numeric($this->data['min']) && $this->data['min'] > $number) {
            $number = $this->data['min'];
        }
        if (is_numeric($this->data['max']) && $this->data['max'] < $number) {
            $number = $this->data['max'];
        }
        $this->value = $number;
    }
}
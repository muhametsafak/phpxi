<?php

function arrayObject($array)
{
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = arrayObject($value);
        }
        $object->$key = $value;
    }
    return $object;
}

<?php

if (!function_exists('decryptIDs')) {
    function decryptIDs($model, $keys)
    {
        foreach ($keys as $index => $key) {
            $keys[$index] = \Vinkla\Hashids\Facades\Hashids::connection($model)->decode($key);
        }
        return $keys;
    }
}

if (!function_exists('randomCharacters')) {
    function randomCharacters($length = 5)
    {
        $name = '';
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $name = $name . $charset[rand($i, (strlen($charset) - 1))];
        }
        return $name;
    }
}

if (!function_exists('randomUnique')) {
    function randomUnique($tableName, $column, $length = 10, $prefix = '')
    {
        $id = str_random($length);
        if (!empty($prefix)) {
            $id = $prefix.$id;
        }
        $validator = \Validator::make(['id' => $id], ['id' => 'unique:'.$tableName.','.$column]);
        if ($validator->fails()) {
            return $this->randomId();
        }
        return $id;
    }
}

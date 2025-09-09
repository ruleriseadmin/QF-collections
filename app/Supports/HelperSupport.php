<?php

namespace App\Supports;

class HelperSupport
{
    public static function snake_to_camel($input, $capitalizeFirstCharacter = false)
    {
        if(! is_array($input)){
            $str = str_replace('_', '', ucwords($input, '-'));

            return $capitalizeFirstCharacter ? lcfirst($str) : $str;
        }
        $newInput = array();

        foreach ($input as $key => $inputs){
            $str = str_replace('_', '', ucwords($key, '_'));
            $str = ! $capitalizeFirstCharacter ? lcfirst($str) : $str;
            $newInput[$str] = $inputs;
        }

        return $newInput;
    }

    public static function camel_to_snake($input)
    {
        if(! is_array($input)){
            preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
            $ret = $matches[0];
            foreach ($ret as &$match) {
                $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
            }
            return implode('_', $ret);
        }
        $newInput = array();
        foreach ($input as $key => $inputs){
            preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $key, $matches);
            $ret = $matches[0];
            foreach ($ret as &$match) {
                $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
            }
            $index = implode('_', $ret);
            $newInput[$index] = $inputs;
        }

        return $newInput;
    }

    public static function getBase64Size($base64String)
    {

        // Calculate the length of the base64 string excluding the data prefix
        $stringLength = strlen($base64String) - strlen('data:image/jpeg;base64,');

        // Calculate the size in bytes
        $sizeInBytes = 4 * ceil($stringLength / 3) * 0.5624896334383812;

        // Convert the size to kilobytes
        $sizeInKb = $sizeInBytes / 1000;

        return $sizeInKb / 1024;
    }

    public static function convert_to_cents($amount): float|int
    {
        return $amount * 100;
    }
}

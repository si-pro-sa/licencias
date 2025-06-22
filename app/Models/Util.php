<?php

namespace App\Models;

class Util
{
    /**
     * Convierte un Array a string para ser utilizado en sentencias SQL
     * [1,2,3] => ('1','2','3')
     * @param array $array
     * @return string
     */
    public static function convertArrayToString(array $array): string
    {
        $string = '(';
        foreach ($array as $key => $value) {
            if ($key === 0) {
                $string .= '\'' . $value . '\'';
            } else {
                $string .= ',\'' . $value . '\'';
            }
        }
        $string .= ')';
        return $string;
    }

    /**
     * Convierte un Array a string para ser utilizado en sentencias SQL
     * [1,2,3] => ('1','2','3')
     * @param array $array
     * @return string
     */
    public static function convertArrayToListOfInt(array $array): string
    {
        $string = '(';
        foreach ($array as $key => $value) {
            if ($key === 0) {
                $string .= $value;
            } else {
                $string .= ',' . $value;
            }
        }
        $string .= ')';
        return $string;
    }
}

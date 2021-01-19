<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('str_slug_tr')) {
    function str_slug_tr($str)
    {
        if (is_array($str)) {
            $str = implode(' ', $str);
        }

        $str = str_replace(
            ['Ö', 'ö', 'Ü', 'ü', 'Ş', 'ş', 'I', 'ı', 'İ'],
            ['O', 'o', 'U', 'u', 'S', 's', 'i', 'i', 'i'],
            $str
        );

        return Str::slug($str);
    }
}

if (!function_exists('generate_string')) {

    /*
     * @param int $length
     * @param string $type
     *
     * @return string
     * */

    function generate_string($length = 10, $type = 'mixed')
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        $range = 9;
        $characters = '';

        if ($type == 'str') {
            $range = 25;
            $characters .= $letters;
        } elseif ($type == 'num') {
            $characters .= $numbers;
        } else {
            $range = 35;
            $characters .= $numbers . $letters;
        }

        $random_string = '';

        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $range)];
        }

        return $random_string;
    }
}

if (!function_exists('date_to_path')) {
    /**
     * returns date path
     * @param number string
     * @return string
     * */
    function date_to_path($date)
    {
        return Carbon::make($date)->format('Y/m/d');
    }
}

if (!function_exists('create_combinations')) {
    function create_combinations($properties)
    {
        $result = array(array());
        foreach ($properties as $property => $values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($values as $value) {
                    $tmp[] = array_merge($result_item, array($property => $value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }
}

if (!function_exists('shorten_string')) {
    /**
     * shortens given string with given length
     * @param $string string
     * @param $length integer
     * @return string
     * */
    function shorten_string($string, $length)
    {
        if (mb_strlen($string) <= $length) {
            return $string;
        }

        $string = substr($string, 0, $length);
        $string = substr($string, 0, strrpos($string, ' '));
        return $string;
    }
}

if (!function_exists('clean_phone_number')) {
    /**
     * formats all phone number like this 905554443322
     * @param $number string
     * @param $prefix string
     * @return string
     * */
    function clean_phone_number($number, $prefix = '0')
    {
        $number = str_replace([' ', '-', '(', ')', '+', '.', ',', '_'], '', $number);
        $length = strlen($number);
        if ($length < 10) {
            return $number;
        }
        $clean_number = $prefix . substr($number, $length - 10, 10);
        return $clean_number;
    }
}

if (!function_exists('uc_sentence')) {
    function uc_sentence($string)
    {
        return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    }
}

if (!function_exists('get_real_ip')) {
    function get_real_ip()
    {
        return isset($_SERVER) && isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : request()->ip();
    }
}

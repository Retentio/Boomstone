<?php

namespace Boomstone\Utils;

/**
 * Description of Toolbox
 *
 * @author Antoine Guiral
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class Toolbox {

    /**
     * Generate random token
     *
     * @param  integer $length
     * @return string
     */
    static public function generateToken($length = 16)
    {
        $shift = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $length = strlen($shift) - 1;

        $token = '';

        for ($i = 0; $i <= $length; ++$i) {
            $token .= $shift[rand(0, $length)];
        }

        return $token;
    }

    /**
     * Return encoded data
     *
     * @param  string  $data
     * @param  string  $salt
     * @param  integer $iteration
     * @return string
     */
    static public function encode($data, $salt, $iteration = 500)
    {
        $encoded = $data;
        for ($i = 0; $i < $iteration; $i++) {
            $encoded = hash('sha512', $encoded.$salt);
        }
        return $encoded;
    }

    /**
     * Generate a slug form a string
     *
     * @param  string  $string
     * @param  integer $length
     * @return string
     */
    static public function generateSlug($string)
    {
        $slug = strtolower($str);
        $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
        $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
        $slug = preg_replace("/\s/", "-", $slug);

        return $slug;
    }

}

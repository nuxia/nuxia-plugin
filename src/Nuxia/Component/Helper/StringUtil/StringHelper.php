<?php

/**
 * @DEPRECATED : This class will be deleted on @NUXIA 3.0
 * Urlize and unaccent will be replaced by https://github.com/Behat/Transliterator
 * Camelize and underscore will be replaced by https://github.com/Doctrine|Inflector
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 **/
class StringHelper
{
    private function __construct()
    {
    }

    public static function camelize($string)
    {
        return Container::camelize($string);
    }

    public static function underscore($string)
    {
        return Container::underscore($string);
    }

    public static function urlize($string, $separator = '-')
    {
        return Urlizer::urlize($string, $separator);
    }

    public static function unaccent($string)
    {
        return Urlizer::unaccent($string);
    }
}

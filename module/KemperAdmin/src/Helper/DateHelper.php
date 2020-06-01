<?php


namespace KemperAdmin\Helper;


class DateHelper
{

    public static function getLastDateofCurrentMonth()
    {
        return date('t') . ' ' . date('M') . ', ' . date('Y');
    }
}
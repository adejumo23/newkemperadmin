<?php
/**
 * Date: 1/21/2020
 * Time: 10:24 PM
 */

namespace KemperAdmin;


class Module
{
    const VERSION = '1';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
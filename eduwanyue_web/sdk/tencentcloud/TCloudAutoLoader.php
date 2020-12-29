<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'GuzzleHttp'.DIRECTORY_SEPARATOR.'functions.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'GuzzleHttp'.DIRECTORY_SEPARATOR.'Psr7'.DIRECTORY_SEPARATOR.'functions.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'GuzzleHttp'.DIRECTORY_SEPARATOR.'Promise'.DIRECTORY_SEPARATOR.'functions.php');

spl_autoload_register(function ($class_name) {
    $clsName = str_replace("\\",DIRECTORY_SEPARATOR, $class_name);
    if (is_file(__DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR.$clsName . '.php')) {
        require_once(__DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR.$clsName.'.php');
    }
});

spl_autoload_register(function ($class_name) {
    $clsName = str_replace("\\",DIRECTORY_SEPARATOR, $class_name);
    if (is_file(__DIR__.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR.$clsName . '.php')) {
        require_once(__DIR__.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR.$clsName.'.php');
    }
});

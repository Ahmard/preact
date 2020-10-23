<?php


namespace Preact;


class Preact implements PreactInterface
{
    use PreactTrait;

    private static $instance;

    public static function getInstance()
    {
        if(! isset(self::$instance)){
            self::$instance = new static();
        }

        return self::$instance;
    }
}
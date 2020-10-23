<?php

use Preact\Event;
use Preact\Preact;

if (!function_exists('event')) {
    function event()
    {
        return Event::getInstance();
    }
}

if (!function_exists('preact')) {
    function preact()
    {
        return Preact::getInstance();
    }
}
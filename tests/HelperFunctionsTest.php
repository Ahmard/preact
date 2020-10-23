<?php


use PHPUnit\Framework\TestCase;
use Preact\Event;
use Preact\Preact;

class HelperFunctionsTest extends TestCase
{
    public function testEventHelperFunction()
    {
        $this->assertEquals(Event::getInstance(), event());
    }

    public function testPreactHelperFunction()
    {
        $this->assertEquals(Preact::getInstance(), preact());
    }
}
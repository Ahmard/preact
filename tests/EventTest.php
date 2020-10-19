<?php


use PHPUnit\Framework\TestCase;
use Preact\Event;

class EventTest extends TestCase
{
    protected $event;

    protected $methodCalled = false;

    public function setUp(): void
    {
        $this->event = new Event();
    }

    public function methodForTest()
    {
        $this->methodCalled = true;
    }

    public function testAddListener()
    {
        $calls = 0;
        $result = $this->event->on('user.created', function () use (&$calls) {
            $calls += 1;
        });

        $this->assertEquals($this->event, $result);

        $this->event->emit('user.created');
        $this->assertEquals(1, $calls);

        $this->event->emit('user.created');
        $this->assertEquals(2, $calls);

    }

    public function testOnceListener()
    {
        $calls = 0;
        $result = $this->event->once('user.created', function () use (&$calls) {
            $calls += 1;
        });

        $this->assertEquals($this->event, $result);

        $this->event->emit('user.created');
        $this->assertEquals(1, $calls);

        $this->event->emit('user.created');
        $this->assertEquals(1, $calls);
    }

    public function testListenerWithArgument()
    {
        $this->event->once('user.created', function ($id) use (&$calls) {
            $this->assertIsInt($id);
        });

        $this->event->emit('user.created', [1]);
    }

    public function testEventWithMethod()
    {
        $returned = $this->event->on('user.created', [$this, 'methodForTest']);
        $this->event->emit('user.created');
        $this->assertEquals($this->event, $returned);

        $this->assertTrue($this->methodCalled);
    }

    public function testRemoveEmptyListener()
    {
        $returned = $this->event->removeListener('user.deleted');
        $this->assertEquals($this->event, $returned);
    }

    public function testRemoveListener()
    {
        $isCalled = false;
        $this->event->on('user.deleted', function () use (&$isCalled) {
            $isCalled = true;
        });
        $this->event->removeListener('user.deleted');
        $this->event->emit('user.deleted');

        $this->assertFalse($isCalled);
    }

    public function testEventWithEmptyListeners()
    {
        $returned = $this->event->emit('user.dummy');

        $this->assertEquals(null, $returned);
    }

    public function testGetListeners()
    {
        $this->event->removeAllListeners();
        $this->event->on('user.created', function () {
        });
        $this->event->on('user.created', 'strlen');

        $listeners = $this->event->getListeners('user.created');
        $this->assertCount(2, $listeners);

        $secondListeners = $this->event->getListeners('user.deleted');
        $this->assertCount(0, $secondListeners);

        $this->event->on('user.updated', function () {
        });

        $allListeners = $this->event->getListeners();

        $this->assertCount(1, $allListeners['user.updated']);
        $this->assertCount(2, $allListeners['user.created']);

        $this->event->removeAllListeners();
        $thirdListeners = $this->event->getListeners();
        $this->assertCount(0, $thirdListeners);
    }
}
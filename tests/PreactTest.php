<?php


use PHPUnit\Framework\TestCase;
use Preact\Preact;
use React\Promise\PromisorInterface;
use React\Promise\RejectedPromise;

class PreactTest extends TestCase
{
    protected $preact;

    protected $methodCalled = false;

    public function methodForTest()
    {
        $this->methodCalled = true;
    }

    public function testAddListener()
    {
        $calls = 0;
        $result = $this->preact->onPreact('user.created', function () use (&$calls) {
            $calls += 1;
        });

        $this->assertEquals($this->preact, $result);

        $this->preact->preact('user.created');
        $this->assertEquals(1, $calls);

        $this->preact->preact('user.created');
        $this->assertEquals(2, $calls);

    }

    public function testOnceListener()
    {
        $calls = 0;
        $this->preact->oncePreact('user.created', function () use (&$calls) {
            $calls += 1;
        });

        $this->preact->preact('user.created');
        $this->assertEquals(1, $calls);

        $this->preact->preact('user.created');
        $this->assertEquals(1, $calls);
    }

    public function testListenerWithArgument()
    {
        $this->preact->oncePreact('user.created', function (PromisorInterface $promisor, $id) use (&$calls) {
            $this->assertIsInt($id);
        });

        $this->preact->preact('user.created', [1]);
    }

    public function testRejectedPromise()
    {
        $this->preact->onPreact('can.create', function (PromisorInterface $promisor, $user) {
            $promisor->reject(false);
        });

        $this->preact
            ->preact('can.create', ['Goat'])
            ->then(function () {
                $this->fail('Should not be called');
            })
            ->otherwise(function ($reason) {
                $this->assertFalse($reason);
            });
    }

    public function testFulfilledPromise()
    {
        $this->preact->onPreact('can.create', function (PromisorInterface $promisor, $user) {
            $promisor->reject(false);
        });

        $this->preact
            ->preact('can.create', ['Goat'])
            ->then(function ($reason) {
                $this->assertFalse($reason);
            })
            ->otherwise(function ($reason) {
                $this->fail('Should not be called');
            });
    }

    public function testEventWithMethod()
    {
        $returned = $this->preact->onPreact('user.created', [$this, 'methodForTest']);
        $this->preact->preact('user.created');
        $this->assertEquals($this->preact, $returned);

        $this->assertTrue($this->methodCalled);
    }

    public function testRemoveEmptyListener()
    {
        $returned = $this->preact->removePreactListener('user.deleted');
        $this->assertEquals($this->preact, $returned);
    }

    public function testRemoveListener()
    {
        $isCalled = false;
        $this->preact->onPreact('user.deleted', function () use (&$isCalled) {
            $isCalled = true;
        });
        $this->preact->removePreactListener('user.deleted');
        $this->preact->preact('user.deleted');

        $this->assertFalse($isCalled);
    }

    public function testEventWithEmptyListeners()
    {
        $returned = $this->preact->preact('user.dummy');

        $this->assertInstanceOf(RejectedPromise::class, $returned);

    }

    public function testGetListeners()
    {
        $this->preact->removeAllPreactListeners();
        $this->preact->onPreact('user.created', function () {
        });
        $this->preact->onPreact('user.created', 'strlen');

        $listeners = $this->preact->getPreactListeners('user.created');
        $this->assertCount(2, $listeners);

        $secondListeners = $this->preact->getPreactListeners('user.deleted');
        $this->assertCount(0, $secondListeners);

        $this->preact->onPreact('user.updated', function () {
        });

        $allListeners = $this->preact->getPreactListeners();

        $this->assertCount(1, $allListeners['user.updated']);
        $this->assertCount(2, $allListeners['user.created']);

        $this->preact->removeAllPreactListeners();
        $thirdListeners = $this->preact->getPreactListeners();
        $this->assertCount(0, $thirdListeners);
    }

    protected function setUp(): void
    {
        $this->preact = new Preact();
    }

}
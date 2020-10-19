<?php

use Preact\EventTrait;
use Preact\PreactTrait;
use React\Promise\PromisorInterface;

require dirname(__DIR__, 1) . '/vendor/autoload.php';

class Animal
{
    use PreactTrait;
    use EventTrait;

    protected $animals;

    public function create(array $animals)
    {
        $this->animals = $animals;

        foreach ($animals as $animal) {
            //Weather to create animal
            $this->preact('can.create', [$animal])
                ->then(function () use ($animal) {
                    $this->emit('created', [$animal]);
                })->otherwise(function () use ($animal) {
                    $this->emit('rejected', [$animal]);
                });
        }
    }

    public function delete()
    {
        foreach ($this->animals as $animal) {
            //Weather to create animal
            $this->preact('can.delete', [$animal])
                ->then(function () use ($animal) {
                    $this->emit('delete.done', [$animal]);
                })->otherwise(function () use ($animal) {
                    $this->emit('delete.rejected', [$animal]);
                });
        }
    }
}

$animal = new Animal();

$animal->onPreact('can.create', function (PromisorInterface $promisor, $animal) {
    if ('Akuya' === $animal['name']) {
        $promisor->resolve();
    } else {
        $promisor->reject();
    }
});

$animal->onPreact('can.delete', function (PromisorInterface $promisor, $animal) {
    if ('Zebra' === $animal['name']) {
        $promisor->resolve();
    } else {
        $promisor->reject();
    }
});


$animal->on('created', function ($animal) {
    echo "Animal created: {$animal['name']}.\n";
});

$animal->on('rejected', function ($animal) {
    echo "Animal creation rejected: {$animal['name']}.\n";
});

$animal->on('delete.done', function ($animal) {
    echo "Animal deleted: {$animal['name']}.\n";
});

$animal->on('delete.rejected', function ($animal) {
    echo "Animal deletion rejected: {$animal['name']}.\n";
});


$animal->create([
    [
        'name' => 'Zebra',
        'height' => '134'
    ],
    [
        'name' => 'Akuya',
        'height' => '23'
    ]
]);

$animal->delete();

$animal->create([
    [
        'name' => 'Camel',
        'height' => '739'
    ]
]);

$animal->delete();
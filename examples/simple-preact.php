<?php

use Preact\Preact;
use React\Promise\PromisorInterface;

require dirname(__DIR__, 1) . '/vendor/autoload.php';

class Animal extends Preact
{
    public function create(array $animals)
    {
        foreach ($animals as $animal) {
            //Weather to create animal
            $this->preact('animal.can-create', [$animal])
                ->then(function () use ($animal) {
                    //CREATE User
                    echo "Animal created: {$animal['name']}\n";
                })->otherwise(function () use ($animal) {
                    echo "Animal creation rejected: {$animal['name']}\n";
                });
        }
    }
}

$animal = new Animal();
$animal->onPreact('animal.can-create', function (PromisorInterface $promisor, $animal) {
    if ('Akuya' === $animal['name']) {
        $promisor->resolve();
    } else {
        $promisor->reject();
    }
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
<?php

use Preact\Event;

require dirname(__DIR__, 1) . '/vendor/autoload.php';

class Human extends Event
{
    public function create(array $users)
    {
        foreach ($users as $user) {
            //CREATE User
            $this->emit('user.created', [$user]);
        }
    }
}

$user = new Human();
$user->on('user.created', function ($user) {
    echo "User created: {$user['username']}.\n";
});

$user->create([
    [
        'username' => 'Ahmard',
        'email' => 'ahmard@test.local'
    ],
    [
        'username' => 'Admin',
        'email' => 'admin@test.local'
    ]
]);
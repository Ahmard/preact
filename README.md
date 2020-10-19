# Preact
Performs action before-hand, confirms action execution.<br/>
This program uses [ReactPHP Promise](https://github.com/reactphp/promise) for its promise implementation. 

## Installation
```bash
composer require ahmard/preact
```

## Usage
1. [Event](#event)
2. [Preact](#preact)

## Event
An event system for simple event-driven programming.
```php
use Preact\Event;

$event = new Event();

$event->on('user.created', function ($user){
    echo "User created: {$user['name']}";
});

$user = [
    'id' => 1,
    'name' => 'Admin'
];
$event->emit('user.created', [$user]);
```

You can use **Preact\EventTrait** trait directly in your class and have the functionality embedded in your code.
```php
namespace App\User;

use Preact\EventTrait;

class User
{
    use EventTrait;
    
    public function create(array $userInfo)
    {
        //Save in DB
        $this->emit('created', [$userInfo]);
    }
}

$user = new User;

$user->on('created', function ($user){
    echo "User created: {$user['username']}\n";
});

$user->create([
    'username' => 'Admin',
    'email' => 'admin@test.local'
]);
```

## Preact
Have confirmation before execution.

```php
use React\Promise\PromisorInterface;
use Preact\PreactTrait;

class Animal
{
    use PreactTrait;
}

$animal = new Animal();

$animal->onPreact('can.create', function (PromisorInterface $promisor, $animalInfo){
    if($animalInfo['name'] == 'lion'){
        $promisor->resolve(true);
    }else{
        $promisor->reject(false);
    }
});

$animal->preact('can.create', ['lion'])
    ->then(function (){
        echo 'Animal creation allowed: lion';
    })
    ->otherwise(function (){
        echo 'Animal creation rejected: lion.\n';
    });
```

To see more use cases view [examples](/examples).

## Licence
**Preact** is MIT licenced.
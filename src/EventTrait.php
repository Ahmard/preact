<?php


namespace Preact;


use InvalidArgumentException;

trait EventTrait
{
    protected $listeners = [];

    /**
     * Listen to given event
     * @param string $eventName
     * @param callable $callback
     * @return $this
     */
    public function on($eventName, callable $callback)
    {
        $eventName = $this->eventValidateName($eventName);
        $this->listeners[$eventName][] = [
            'type' => 'regular',
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Make sure that the given event name is not null
     * @param $eventName
     * @return string
     */
    protected function eventValidateName($eventName)
    {
        if (null === $eventName) {
            throw new InvalidArgumentException('Event name must not be null.');
        }

        return $eventName;
    }

    /**
     * Listen to given event once and then remove it
     * @param string $eventName
     * @param callable $callback
     * @return $this
     */
    public function once($eventName, callable $callback)
    {
        $eventName = $this->eventValidateName($eventName);
        $this->listeners[$eventName][] = [
            'type' => 'once',
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Emit an event
     * @param string $eventName
     * @param mixed ...$arguments
     * @return null
     */
    public function emit($eventName, array $arguments = [])
    {
        $eventName = $this->eventValidateName($eventName);

        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $key => $listener) {
                call_user_func_array($listener['callback'], $arguments);

                if ('once' === $listener['type']) {
                    unset($this->listeners[$eventName][$key]);
                }
            }
        }
    }

    /**
     * Get event listeners
     * @param string $eventName
     * @return array
     */
    public function getListeners($eventName = null)
    {
        $getListeners = function ($listeners) {
            $theListeners = [];
            foreach ($listeners as $key => $listener) {
                if (isset($listener['callback'])) {
                    $theListeners[] = $listener['callback'];
                } else {
                    foreach ($listener as $dListener) {
                        $theListeners[$key][] = $dListener['callback'];
                    }
                }
            }

            return $theListeners;
        };

        if (null !== $eventName) {
            if (isset($this->listeners[$eventName])) {
                return $getListeners($this->listeners[$eventName]);
            }

            return [];
        }

        return $getListeners($this->listeners);
    }

    /**
     * Remove specific event listener
     * @param string $eventName
     * @return $this
     */
    public function removeListener($eventName)
    {
        $eventName = $this->eventValidateName($eventName);

        unset($this->listeners[$eventName]);

        return $this;
    }

    /**
     * Remove all event listeners associated with this object
     * @return $this
     */
    public function removeAllListeners()
    {
        $this->listeners = [];

        return $this;
    }
}
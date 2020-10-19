<?php


namespace Preact;


interface EventInterface
{
    /**
     * Bind listener to an event
     * @param string $eventName
     * @param callable $listener
     * @return $this
     */
    public function on($eventName, callable $listener);

    /**
     * Bind listener to once event
     * @param $eventName
     * @param callable $listener
     * @return mixed
     */
    public function once($eventName, callable $listener);

    /**
     * Remove event listener
     * @param $eventName
     * @return mixed
     */
    public function removeListener($eventName);

    /**
     * Remove all event listeners in this object
     * @return $this
     */
    public function removeAllListeners();

    /**
     * Get all event listeners
     * @param null $eventName
     * @return array
     */
    public function getListeners($eventName = null);

    /**
     * Emit event
     * @param $eventName
     * @param array $arguments
     * @return $this
     */
    public function emit($eventName, array $arguments = []);
}
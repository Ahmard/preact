<?php


namespace Preact;


interface PreactInterface
{
    /**
     * Bind listener to an preact
     * @param string $preactName
     * @param callable $listener
     * @return $this
     */
    public function onPreact($preactName, callable $listener);

    /**
     * Bind listener to once preact
     * @param $preactName
     * @param callable $listener
     * @return mixed
     */
    public function oncePreact($preactName, callable $listener);

    /**
     * Remove preact listener
     * @param $preactName
     * @return mixed
     */
    public function removePreactListener($preactName);

    /**
     * Remove all preact listeners in this object
     * @return $this
     */
    public function removeAllPreactListeners();

    /**
     * Get all preact listeners
     * @param null $preactName
     * @return array
     */
    public function getPreactListeners($preactName = null);

    /**
     * Emit preact
     * @param $preactName
     * @param array $arguments
     * @return $this
     */
    public function preact($preactName, array $arguments = []);
}
<?php


namespace Preact;


use InvalidArgumentException;
use React\Promise\Deferred;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use function React\Promise\reject;

trait PreactTrait
{
    protected $preactListeners = [];

    /**
     * Listen to given preact
     * @param string $preactName
     * @param callable $callback
     * @return $this
     */
    public function onPreact($preactName, callable $callback)
    {
        $preactName = $this->preactValidateName($preactName);
        $this->preactListeners[$preactName][] = [
            'type' => 'regular',
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Make sure that the given preact name is not null
     * @param $preactName
     * @return string
     */
    protected function preactValidateName($preactName)
    {
        if (null === $preactName) {
            throw new InvalidArgumentException('Preact name must not be null.');
        }

        return $preactName;
    }

    /**
     * Listen to given preact once and then remove it
     * @param string $preactName
     * @param callable $callback
     * @return $this
     */
    public function oncePreact($preactName, callable $callback)
    {
        $preactName = $this->preactValidateName($preactName);
        $this->preactListeners[$preactName][] = [
            'type' => 'once',
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Emit an preact
     * @param string $preactName
     * @param mixed ...$arguments
     * @return mixed|Promise|PromiseInterface|null
     */
    public function preact($preactName, array $arguments = [])
    {
        $preactName = $this->preactValidateName($preactName);
        $hasListeners = false;
        $deferred = new Deferred();

        if (!isset($this->preactListeners[$preactName])) {
            return reject(new InvalidArgumentException("No preact with name {$preactName}"));
        }

        /**
         * Promise emitting helper,
         * just to avoid code repetition
         * @param $preactListeners
         */
        foreach ($this->preactListeners[$preactName] as $key => $listener) {
            $parameters = array_merge([$deferred], $arguments);
            call_user_func_array($listener['callback'], $parameters);

            if ('once' === $listener['type']) {
                unset($this->preactListeners[$preactName][$key]);
            }
        }

        return $deferred->promise();
    }

    /**
     * Get preact listeners
     * @param string $preactName
     * @return array
     */
    public function getPreactListeners($preactName = null)
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

        if (null !== $preactName) {
            if (isset($this->preactListeners[$preactName])) {
                return $getListeners($this->preactListeners[$preactName]);
            }

            return [];
        }

        return $getListeners($this->preactListeners);
    }

    /**
     * Remove specific preact listener
     * @param string $preactName
     * @return $this
     */
    public function removePreactListener($preactName)
    {
        $preactName = $this->preactValidateName($preactName);

        unset($this->preactListeners[$preactName]);

        return $this;
    }

    /**
     * Remove all preact listeners associated with this object
     * @return $this
     */
    public function removeAllPreactListeners()
    {
        $this->preactListeners = [];

        return $this;
    }
}
<?php

namespace Gephart\EventManager;

/**
 * Event manager
 *
 * @package Gephart\EventManager
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.2
 */
class Event implements EventInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var object|null
     */
    private $targer;

    /**
     * @var array
     */
    private $params;

    /**
     * @var bool
     */
    private $stopPropagation = false;

    /**
     * Get event name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get target/context from which event was triggered
     *
     * @return object|null
     */
    public function getTarget()
    {
        return $this->targer;
    }

    /**
     * Get parameters passed to the event
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get a single parameter by name
     *
     * @param string $name
     * @return bool|mixed
     */
    public function getParam(string $name)
    {
        return (isset($this->params[$name]))?$this->params[$name]:false;
    }

    /**
     * Set the event name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set the event target
     *
     * @param object|null $target
     */
    public function setTarget($target = null)
    {
        $this->targer = $target;
    }

    /**
     * Set event parameters
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param bool $flag
     */
    public function stopPropagation(bool $flag)
    {
        $this->stopPropagation = $flag;
    }

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }
}

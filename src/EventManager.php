<?php

namespace Gephart\EventManager;

/**
 * Event manager
 *
 * @package Gephart\EventManager
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.2
 */
final class EventManager implements EventManagerInterface
{
    /**
     * @var array
     */
    private $listeners = [];

    /**
     * Attaches a listener to an event
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     * @return bool
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool
    {
        $this->detach($event, $callback);

        $this->listeners[] = [
            "event" => $event,
            "callback" => $callback,
            "priority" => $priority
        ];

        usort($this->listeners, function ($left, $right) {
            return $left["priority"] < $right["priority"];
        });

        return true;
    }

    /**
     * Detaches a listener from an event
     *
     * @param string $event
     * @param callable $callback
     * @return bool
     */
    public function detach(string $event, callable $callback): bool
    {
        foreach ($this->listeners as $key => $listener) {
            if ($listener["event"] == $event && $listener["callback"] == $callback) {
                unset($this->listeners[$key]);
                return true;
            }
        }

        return false;
    }

    /**
     * Get all listeners
     *
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * Clear all listeners for a given event
     *
     * @param string $event
     */
    public function clearListeners(string $event)
    {
        foreach ($this->listeners as $key => $listener) {
            if ($listener["event"] == $event) {
                unset($this->listeners[$key]);
            }
        }
    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param EventInterface|string $event
     * @param null $target
     * @param array $argv
     * @return bool
     * @throws \Exception
     */
    public function trigger($event, $target = null, array $argv = [])
    {
        if (is_string($event)) {
            $eventName = $event;
            $event = new Event();
            $event->setName($eventName);
            $event->setTarget($target);
            $event->setParams($argv);
        } elseif ($event instanceof EventInterface) {
            $eventName = $event->getName();
        }

        if (empty($eventName)) {
            throw new \Exception("EventManager: Param event must be string of instance of EventInterface");
        }

        $result = false;

        foreach ($this->listeners as $listener) {
            if ($listener["event"] == $eventName) {
                $result = $listener["callback"]($event);

                if ($event->isPropagationStopped()) {
                    return $result;
                }
            }
        }

        return $result;
    }
}

<?php

namespace Gephart\EventManager;

final class EventManager implements EventManagerInterface
{
    /**
     * @var array
     */
    private $listeners = [];

    /**
     * Attaches a listener to an event
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool
    {
        $this->detach($event, $callback);

        $this->listeners[] = [
            "event" => $event,
            "callback" => $callback,
            "priority" => $priority
        ];

        usort($this->listeners, function ($a, $b){
            return $a["priority"] < $b["priority"];
        });

        return true;
    }

    /**
     * Detaches a listener from an event
     */
    public function detach(string $event, callable $callback): bool
    {
        foreach($this->listeners as $key=>$listener) {
            if ($listener["event"] == $event && $listener["callback"] == $callback) {
                unset($this->listeners[$key]);
                return true;
            }
        }

        return false;
    }

    /**
     * Clear all listeners for a given event
     */
    public function clearListeners(string $event)
    {
        foreach($this->listeners as $key=>$listener) {
            if ($listener["event"] == $event) {
                unset($this->listeners[$key]);
            }
        }
    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     */
    public function trigger($event, object $target = null, array $argv = [])
    {
        if (is_string($event)) {
            $event_name = $event;
            $event = new Event();
            $event->setName($event_name);
            $event->setTarget($target);
            $event->setParams($argv);
        } elseif ($event instanceof EventInterface) {
            $event_name = $event->getName();
        } else {
            throw new \Exception("EventManager: Param event must be string of instance of EventInterface");
        }

        $result = false;

        foreach($this->listeners as $key=>$listener) {
            if ($listener["event"] == $event_name) {
                $result = $listener["callback"]($event);

                if ($event->isPropagationStopped()) {
                    return $result;
                }
            }
        }

        return $result;
    }
}
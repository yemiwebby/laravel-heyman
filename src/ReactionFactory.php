<?php

namespace Imanghafoori\HeyMan;

class ReactionFactory
{
    /**
     * @var \Imanghafoori\HeyMan\Chain
     */
    private $chain;

    /**
     * ListenerFactory constructor.
     *
     * @param \Imanghafoori\HeyMan\Chain $chain
     */
    public function __construct(Chain $chain)
    {
        $this->chain = $chain;
    }

    /**
     * @return \Closure
     */
    public function make(): \Closure
    {
        $responder = app(ResponderFactory::class)->make();

        return $this->makeReaction($responder);
    }

    /**
     * @param $responder
     *
     * @return \Closure
     */
    private function makeReaction(callable $responder): \Closure
    {
        $dispatcher = $this->eventsToDispatch();
        $calls = $this->methodsToCall();

        $cb = $this->chain->predicate;
        $this->chain->reset();

        return function (...$f) use ($responder, $cb, $dispatcher, $calls) {
            if ($cb($f)) {
                return true;
            }

            $calls();
            $dispatcher();
            $responder();
        };
    }

    private function eventsToDispatch(): \Closure
    {
        $events = $this->chain->events;

        if (!$events) {
            return function () {
            };
        }

        return function () use ($events) {
            foreach ($events as $event) {
                app('events')->dispatch(...$event);
            }
        };
    }

    private function methodsToCall(): \Closure
    {
        $calls = $this->chain->afterCalls;

        if (!$calls) {
            return function () {
            };
        }

        return function () use ($calls) {
            foreach ($calls as $call) {
                app()->call(...$call);
            }
        };
    }
}
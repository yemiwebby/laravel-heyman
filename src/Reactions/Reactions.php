<?php

namespace Imanghafoori\HeyMan\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Imanghafoori\HeyMan\Chain;

class Reactions
{
    /**
     * @var \Imanghafoori\HeyMan\Chain
     */
    private $chain;

    /**
     * Actions constructor.
     *
     * @param \Imanghafoori\HeyMan\Chain $chain
     */
    public function __construct(Chain $chain)
    {
        $this->chain = $chain;
    }

    public function response(): Responder
    {
        return new Responder($this->chain, $this);
    }

    public function redirect(): Redirector
    {
        return new Redirector($this->chain, $this);
    }

    public function afterCalling($callback, array $parameters = []): self
    {
        $this->chain->addAfterCall($callback, $parameters);

        return $this;
    }

    public function weThrowNew(string $exception, string $message = '')
    {
        $this->chain->addException($exception, $message);
    }

    public function abort($code, string $message = '', array $headers = [])
    {
        $this->chain->addAbort($code, $message, $headers);
    }

    public function weRespondFrom($callback, array $parameters = [])
    {
        $this->chain->addRespondFrom($callback, $parameters);
    }

    public function weDenyAccess(string $message = '')
    {
        $this->chain->addException(AuthorizationException::class, $message);
    }

    public function afterFiringEvent($event, $payload = [], $halt = false): self
    {
        $this->chain->eventFire($event, $payload, $halt);

        return $this;
    }

    public function __destruct()
    {
        app(Chain::class)->submitChainConfig();
    }
}

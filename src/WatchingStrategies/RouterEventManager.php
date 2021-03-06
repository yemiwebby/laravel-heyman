<?php

namespace Imanghafoori\HeyMan\WatchingStrategies;

use Illuminate\Support\Str;

class RouterEventManager extends BaseManager
{
    protected $type = 'route';

    public function findMatchingCallbacks(array $matchedRoute): array
    {
        $matchedCallbacks = [];
        foreach ($this->data as $routeInfo => $callBacks) {
            foreach ($matchedRoute as $info) {
                if (Str::is($routeInfo, $info)) {
                    $matchedCallbacks[] = $callBacks['default'];
                }
            }
        }

        return $matchedCallbacks;
    }
}

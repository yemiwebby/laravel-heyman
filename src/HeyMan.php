<?php

namespace Imanghafoori\HeyMan;

class HeyMan
{
    private $urls = [];

    private $target;

    private $value;

    public function whenVisitingUrl($url)
    {
        $this->value = $url;
        $this->target = 'urls';

        return $this;
    }

    public function getUrls()
    {
        return $this->urls;
    }

}
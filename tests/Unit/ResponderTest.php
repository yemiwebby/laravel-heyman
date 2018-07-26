<?php

class ResponderTest extends TestCase
{
    public function testRedirectToIntended()
    {
        $methods = [
            'stream',
            'streamDownload',
            'view',
            'make',
            'json',
            'jsonp',
            'download',
        ];
        foreach ($methods as $method) {
            $param = str_random(3);
            $reaction = app(\Imanghafoori\HeyMan\Actions::class);
            \Facades\Imanghafoori\HeyMan\Chain::shouldReceive('submitChainConfig')->once();
            \Facades\Imanghafoori\HeyMan\Chain::shouldReceive('addResponse');
            $reaction->response()->{$method}($param);
        }
    }

    public function testRedirectWith()
    {
        $methods2 = [
            'with',
            'withCookies',
            'withInput',
            'onlyInput',
            'exceptInput',
            'withErrors',
        ];

        $methods = [
            'intended',
            'action',
            'route',
            'guest',
            'to',
        ];

        foreach ($methods2 as $method2) {
            foreach ($methods as $method) {
                $param = str_random(3);
                $reaction = app(\Imanghafoori\HeyMan\Actions::class);
                \Facades\Imanghafoori\HeyMan\Chain::shouldReceive('submitChainConfig')->once();
                \Facades\Imanghafoori\HeyMan\Chain::shouldReceive('addRedirect');

                $reaction->redirect()->{$method}($param)->{$method2}(['key', 'value'])->with(['a', 'b']);
            }
        }
    }
}

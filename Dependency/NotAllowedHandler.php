<?php

namespace service\Dependency;

class NotAllowedHandler
{

    public function set(\Slim\Container $container)
    {
     
        $container['notAllowedHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                return $c['response']
                                ->withStatus(405)
                                ->withJson(['status' => 'fail', 'message' => 'Method: '.$request->getMethod().' not allowed']);
            };
        };
    }

}
//


<?php

namespace service\Dependency;

class NotFoundHandler
{

    public function set(\Slim\Container $container)
    {
        $container['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                return $c['response']
                                ->withStatus(404)
                                ->withJson(['status' => 'fail', 'message' => 'URL/Resource not found']);
            };
        };
    }

}

//
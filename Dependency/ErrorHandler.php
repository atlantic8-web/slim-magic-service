<?php

namespace service\Dependency;

class ErrorHandler
{

    public function set(\Slim\Container $sc)
    {
       
        $sc['errorHandler'] = function ($sc) {
            return function ($request, $response, $exception) use (&$sc) {
                
                $sc->logger->write([$exception->getMessage(), $exception->getFile(), $exception->getLine()]);
                
                 $showError = $sc->settings['displayErrorDetails'];
                $message = ($showError == true) ? $exception : 'Something went wrong, contact the administrator';
                $response->getBody()->rewind();
                return $response->withStatus(500)
                                ->withHeader('Content-Type', 'application/json')
                                ->write($message);
            };
        };
    }

}

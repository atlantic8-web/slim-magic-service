<?php

//For PHP7

namespace service\Dependency;

class PhpErrorHandler
{

    public function set(\Slim\Container $container)
    {
        $container['phpErrorHandler'] = function ($container) {
            //errorHandler must be set up and called before this class, see config/slim-magic.php
            return $container['errorHandler'];
        };
    }

}

<?php

/**
 * CONSOLE USAGE > php index.php development /your-slim-uri-path GET vara=1&varb=2&varc=3 
 * the first argument is the environment, use production, staging or development etc. and handle it in the bootstrap
 * the last argument is an optional query string
 */
namespace service\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Cli
{

    protected $sc;
    protected $request = null;
    protected $argv;

    public function __construct(\Slim\Container $container)
    {

        $this->sc = $container;
        $this->argv = isset($container->argv) ? $container->argv : null;
    }

     public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a value from an array if exists otherwise return a default value
     *
     * @param   array   $argv
     * @param   integer $key
     * @param   mixed   $default
     * @return  string
     */
    private function getArg($key, $default = '')
    {
        if (!array_key_exists($key, $this->argv)) {
            return $default;
        }

        return $this->argv[$key];
    }

    /**
     * Construct the URI if path and params are being passed
     *
     * @param string $path
     * @param string $params
     * @return string
     */
    private function getUri($path, $params)
    {
        $uri = '/';
        if (strlen($path) > 0) {
            $uri = $path;
        }

        if (strlen($params) > 0) {
            $uri .= '?' . $params;
        }

        return $uri;
    }
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
     
        $this->request = $request;
      
        if ($this->argv) {

            $path   = $this->getArg(2);
            $method = $this->getArg(3);
            $params = $this->getArg(4);
           
             if (strtoupper($method) === 'GET') {
                $this->request = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock([
                    'REQUEST_METHOD'    => 'GET',
                    'REQUEST_URI'       => $this->getUri($path, $params),
                    'QUERY_STRING'      => $params
                ]));
            }

            unset($this->argv);
        }

        return $next($this->request, $response);
    }
    

}

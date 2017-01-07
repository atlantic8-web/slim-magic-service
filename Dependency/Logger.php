<?php

namespace service\Dependency;

class Logger
{

    protected $logFile;

    public function set(\Slim\Container $sc)
    {
        $this->logFile = $sc->settings['errorLogFile'];
        $sc['logger'] = $this;
    }

    public function write(array $object)
    {
        $final = '';
        foreach ($object as $o) {
            $string = (is_array($o) || is_object($o)) ? print_r($o, true) : $o;
            $final .= date('Y-m-d H:i:s') . ' ' . $string . ' ' . "\n";
        }

        file_put_contents($this->logFile, $final, FILE_APPEND);
    }

}

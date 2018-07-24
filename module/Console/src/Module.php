<?php

namespace Console;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements
    ConfigProviderInterface,
    ConsoleUsageProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleUsage(Console $console): array
    {
        return [
            'http:traffic:monitor'
            => 'Monitor HTTP Traffic',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Console\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MVar\Apache2LogParser\AccessLogParser;
use Console\Rule;

class HttpTrafficControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): HttpTrafficController {

        $console = $container->get('Console');
        $parser = new AccessLogParser(AccessLogParser::FORMAT_COMMON);
        $rules = [
            $container->get(Rule\ShortStats::class),
            $container->get(Rule\HighTraffic::class),
        ];

        $instance = new HttpTrafficController(
            $console,
            $parser,
            $rules
        );

        return $instance;
    }
}

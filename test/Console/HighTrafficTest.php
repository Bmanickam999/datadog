<?php

namespace ApplicationTest\Console;

use ApplicationTest\AbstractTest;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;

class HighTrafficTest extends AbstractTest
{
    public function testFireAlert()
    {
        // Catch the console commands
        $expect = [];

        $console = $this->createMock('Zend\Console\Adapter\AdapterInterface');
        $console->method('writeLine')
            ->with($this->callback(function ($subject) use (& $expect) {
                $expect[] = $subject;

                return true;
            }));

        $params = $this->createMock('Zend\Mvc\Controller\Plugin\Params');
        $params->method('fromRoute')
            ->willReturn(0.25);

        $lines = [
            0 => [
                '52.239.116.55 - - [23/Jul/2018:22:42:25 -0600] "GET /wp-content HTTP/1.0" 200 5078',
                '165.90.103.179 - - [23/Jul/2018:22:45:04 -0600] "POST /apps/cart.jsp?appID=7264 HTTP/1.0" 200 4953',
                '87.119.124.147 - - [23/Jul/2018:22:46:37 -0600] "GET /app/main/posts HTTP/1.0" 200 5029',
                '92.76.180.118 - - [23/Jul/2018:22:51:33 -0600] "GET /list HTTP/1.0" 200 5019',
                '96.23.239.158 - - [23/Jul/2018:22:53:57 -0600] "GET /search/tag/list HTTP/1.0" 200 5034',
            ],
        ];
        $runNumber = 0;

        $highTraffic = $this->getApplication()->getServiceManager()
            ->get(\Console\Rule\HighTraffic::class);

        $highTraffic($console, $params, $lines, $runNumber);

        $this->assertEquals(substr($expect[0], 0, 42), 'High traffic generated an alert - hits = 5');
        $this->assertEquals($expect[1], 'High traffic measured at 2');
    }

    public function testContinuedAlert()
    {
        // Catch the console commands
        $expect = [];

        $console = $this->createMock('Zend\Console\Adapter\AdapterInterface');
        $console->method('writeLine')
            ->with($this->callback(function ($subject) use (& $expect) {
                $expect[] = $subject;

                return true;
            }));

        $params = $this->createMock('Zend\Mvc\Controller\Plugin\Params');
        $params->method('fromRoute')
            ->willReturn(0.25);

        $lines = [
            0 => [
                '52.239.116.55 - - [23/Jul/2018:22:42:25 -0600] "GET /wp-content HTTP/1.0" 200 5078',
                '165.90.103.179 - - [23/Jul/2018:22:45:04 -0600] "POST /apps/cart.jsp?appID=7264 HTTP/1.0" 200 4953',
                '87.119.124.147 - - [23/Jul/2018:22:46:37 -0600] "GET /app/main/posts HTTP/1.0" 200 5029',
                '92.76.180.118 - - [23/Jul/2018:22:51:33 -0600] "GET /list HTTP/1.0" 200 5019',
                '96.23.239.158 - - [23/Jul/2018:22:53:57 -0600] "GET /search/tag/list HTTP/1.0" 200 5034',
            ],
        ];
        $runNumber = 0;

        $highTraffic = $this->getApplication()->getServiceManager()
            ->get(\Console\Rule\HighTraffic::class);

        $highTraffic($console, $params, $lines, $runNumber);
        $highTraffic($console, $params, $lines, $runNumber);

        $this->assertEquals(substr($expect[2], 0, 28), 'High traffic alert continues');
    }

    public function testClearAlert()
    {
        // Catch the console commands
        $expect = [];

        $console = $this->createMock('Zend\Console\Adapter\AdapterInterface');
        $console->method('writeLine')
            ->with($this->callback(function ($subject) use (& $expect) {
                $expect[] = $subject;

                return true;
            }));

        $params = $this->createMock('Zend\Mvc\Controller\Plugin\Params');
        $params->method('fromRoute')
            ->willReturn(0.25);

        $lines = [
            0 => [
                '52.239.116.55 - - [23/Jul/2018:22:42:25 -0600] "GET /wp-content HTTP/1.0" 200 5078',
                '165.90.103.179 - - [23/Jul/2018:22:45:04 -0600] "POST /apps/cart.jsp?appID=7264 HTTP/1.0" 200 4953',
                '87.119.124.147 - - [23/Jul/2018:22:46:37 -0600] "GET /app/main/posts HTTP/1.0" 200 5029',
                '92.76.180.118 - - [23/Jul/2018:22:51:33 -0600] "GET /list HTTP/1.0" 200 5019',
                '96.23.239.158 - - [23/Jul/2018:22:53:57 -0600] "GET /search/tag/list HTTP/1.0" 200 5034',
            ],
        ];
        $runNumber = 0;

        $highTraffic = $this->getApplication()->getServiceManager()
            ->get(\Console\Rule\HighTraffic::class);

        // Trigger high traffic
        $highTraffic($console, $params, $lines, $runNumber);

        $lines = [ 0 => [] ];

        // Clear high traffic
        $highTraffic($console, $params, $lines, $runNumber);

        $this->assertEquals(substr($expect[2], 0, 35), 'High traffic alert has recovered at');
    }

    public function testNoAlert()
    {
        // Catch the console commands
        $expect = [];

        $console = $this->createMock('Zend\Console\Adapter\AdapterInterface');
        $console->method('writeLine')
            ->with($this->callback(function ($subject) use (& $expect) {
                $expect[] = $subject;

                return true;
            }));

        $params = $this->createMock('Zend\Mvc\Controller\Plugin\Params');
        $params->method('fromRoute')
            ->willReturn(5);

        $lines = [
            0 => [
                '52.239.116.55 - - [23/Jul/2018:22:42:25 -0600] "GET /wp-content HTTP/1.0" 200 5078',
                '165.90.103.179 - - [23/Jul/2018:22:45:04 -0600] "POST /apps/cart.jsp?appID=7264 HTTP/1.0" 200 4953',
                '87.119.124.147 - - [23/Jul/2018:22:46:37 -0600] "GET /app/main/posts HTTP/1.0" 200 5029',
                '92.76.180.118 - - [23/Jul/2018:22:51:33 -0600] "GET /list HTTP/1.0" 200 5019',
                '96.23.239.158 - - [23/Jul/2018:22:53:57 -0600] "GET /search/tag/list HTTP/1.0" 200 5034',
            ],
        ];
        $runNumber = 0;

        $highTraffic = $this->getApplication()->getServiceManager()
            ->get(\Console\Rule\HighTraffic::class);

        $highTraffic($console, $params, $lines, $runNumber);

        $this->assertEmpty($expect);
    }
}

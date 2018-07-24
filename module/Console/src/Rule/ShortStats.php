<?php

declare(strict_types=1);

namespace Console\Rule;

use Zend\Mvc\Controller\Plugin\Params;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;
use Zend\Console\ColorInterface as Color;

class ShortStats implements
    RuleInterface
{
    public function __invoke(ConsoleAdapter $console, Params $params, array $lines, int $runNumber)
    {
        // If there are no new lines just return
        if (! sizeof($lines[$runNumber])) {
            return;
        }

        $console->writeLine("Short Stats", Color::CYAN);

        $path = [];
        $visitors = [];
        $identity = [];
        $bytes = 0;
        $four04 = 0;

        foreach ($lines[$runNumber] as $line) {
            $visitors[] = $line['remote_host'];
            $identity[] = $line['identity'];
            $path[] = $line['request']['path'];
            $bytes += $line['bytes_sent'];
            if ($line['response_code'] == 404) {
                $four04 ++;
            }
        }

        $sectionCount = [];
        foreach ($path as $p) {
            $section = strtok($p, '/');
            if (!$section) {
                $section = '/';
            }
            $sectionCount[$section] = $sectionCount[$section] ?? 0;
            $sectionCount[$section] ++;
        }
        asort($sectionCount);
        $sectionCount = array_reverse($sectionCount);

        $console->writeLine("Requests: " . sizeof($lines[$runNumber]), Color::YELLOW);
        $console->writeLine("Unique Hosts: " . sizeof(array_unique($visitors)), Color::YELLOW);
        $console->writeLine("Unique Identity: " . sizeof(array_unique($identity)), Color::YELLOW);
        $console->writeLine("Bytes Sent: " . $bytes, Color::YELLOW);
        $console->writeLine("404: " . $four04, Color::YELLOW);
        $console->writeLine("Popular Sections:", Color::YELLOW);
        $sectionCount = array_slice($sectionCount, 0, 5);
        foreach ($sectionCount as $section => $count) {
            $console->writeLine('    ' . $section . ' - ' . $count, Color::YELLOW);
        }
    }
}

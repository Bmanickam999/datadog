<?php

declare(strict_types=1);

namespace Console\Rule;

use DateTime;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;
use Zend\Console\ColorInterface as Color;

final class HighTraffic implements
    RuleInterface
{
    private $isAlerting = false;
    private $alertingAt = null;

    public function __invoke(ConsoleAdapter $console, Params $params, array $lines, int $runNumber)
    {
        $threshold = $params->fromRoute('threshold-alert') ?? 10; // Per second
        $seconds = 0;
        $totalLines = 0;

        // Get total traffic for last 2 minutes
        foreach ($lines as $group) {
            $totalLines += sizeof($group);
            $seconds += 10;
        }

        // If threshold is met notify the user
        $trafficMeasure = ($totalLines / ($threshold * $seconds));
        if ($trafficMeasure > 1) {
            if ($this->isAlerting) {
                $console->writeLine(
                    "High traffic alert continues - hits = "
                        . $totalLines . ", triggered at "
                        . $this->alertingAt,
                    Color::RED
                );

                $console->writeLine("High traffic measured at $trafficMeasure", Color::RED);

                return;
            }

            $this->isAlerting = true;
            $this->alertingAt = (new DateTime())->format('Y-m-d H:i:s.u');

            $console->writeLine(
                "High traffic generated an alert - hits = "
                    . $totalLines . ", triggered at "
                    . $this->alertingAt,
                Color::RED
            );

            $console->writeLine("High traffic measured at $trafficMeasure", Color::RED);

            $this->isAlerting = true;
        } else {
            if (! $this->isAlerting) {
                return;
            }

            $console->writeLine(
                "High traffic alert has recovered at "
                    . (new DateTime())->format('Y-m-d H:i:s.u'),
                Color::GREEN
            );

            $this->isAlerting = false;
            $this->alertingAt = null;
        }
    }
}

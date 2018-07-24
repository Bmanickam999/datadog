<?php

declare(strict_types=1);

namespace Console\Controller;

use DateTime;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;
use Zend\Console\ColorInterface as Color;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Console\Prompt;
use Zend\Console\ProgreesBar as ConsoleProgressBar;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ConsoleProgressBarAdapter;

use MVar\Apache2LogParser\AccessLogParser;
use MVar\LogParser\LogIterator;
use MVar\LogParser\Exception\ParserException;

class HttpTrafficController extends AbstractConsoleController
{
    private $parser;
    private $rules;

    public function __construct(ConsoleAdapter $console, AccessLogParser $parser, array $rules)
    {
        $this->setConsole($console);
        $this->parser = $parser;
        $this->rules = $rules;
    }

    public function indexAction()
    {
        // Verify log exists
        $log = $this->params()->fromRoute('log') ?? '/var/log/access.log';
        if (! file_exists($log)) {
            $this->getConsole()->writeLine("Log file $log does not exist.", Color::RED);
            return $this->getResponse();
        }

        // Open log and move file pointer to end of file
        $file = fopen($log, 'r');
        fseek($file, 0, SEEK_END);

        // Announce
        $this->getConsole()->writeLine("Datadog Coding Challenge", Color::CYAN);
        $this->getConsole()->writeLine("Opened file $log", Color::CYAN);
        $this->getConsole()->writeLine("This tool will output log statistics every 10 seconds.", Color::YELLOW);

        /**
         * Main program loop
         *
         * Because we collect data every 2 minutes and every 10 seconds we track
         * data in 10 second increments in an array of 12 elements
         */
        $lines = [];
        $runNumber = 0;

        while (true) {
            // Find our run number of 0 to 11
            if ($runNumber == 11) {
                $runNumber = 0;
            } else {
                $runNumber++;
            }

            // Show a progress bar for each 10 second interval
            $adapter = new ConsoleProgressBarAdapter();
            $adapter->setWidth(12);
            $adapter->setElements([ConsoleProgressBarAdapter::ELEMENT_BAR]);
            $progressBar = new ProgressBar($adapter, 0, 10);

            for ($i = 1; $i <= 10; $i++) {
                sleep(1);
                $progressBar->update($i);
            }

            // Parse any new lines in the log then seek to end of file
            $counter = 0;
            $lines[$runNumber] = [];
            while ($line = fgets($file)) {
                $counter ++;
                $lines[$runNumber][] = $this->parser->parseLine($line);
            }
            fseek($file, 0, SEEK_END);

            $this->getConsole()->writeLine(' ' . (new DateTime())->format('Y-m-d H:i:s.u'));

            // Run rules
            foreach ($this->rules as $rule) {
                $rule($this->getConsole(), $this->params(), $lines, $runNumber);
            }

            $this->getConsole()->writeLine($counter . ' new lines in log', Color::CYAN);

            $lineCount = 0;
            foreach ($lines as $group => $detail) {
                $lineCount += sizeof($detail);
            }
            $lineSeconds = sizeof($lines) * 10;

            $this->getConsole()->writeLine(
                $lineCount
                    . ' new lines in last '
                    . $lineSeconds
                    . ' seconds.',
                Color::CYAN
            );
        }
    }
}

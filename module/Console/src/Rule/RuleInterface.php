<?php

declare(strict_types=1);

namespace Console\Rule;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;
use Zend\Mvc\Controller\Plugin\Params;

interface RuleInterface
{
    public function __invoke(ConsoleAdapter $console, Params $params, array $lines, int $runNumber);
}

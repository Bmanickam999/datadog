<?php

namespace ApplicationTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/test.config.php'
        );
        parent::setUp();
    }
}

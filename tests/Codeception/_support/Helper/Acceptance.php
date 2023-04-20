<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Codeception\Helper;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use OxidEsales\Facts\Facts;

final class Acceptance extends \Codeception\Module
{
    public function _beforeSuite($settings = []): void
    {
        exec((new Facts())->getCommunityEditionRootPath() . '/bin/oe-console oe:module:activate oegdproptin');
    }
}

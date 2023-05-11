<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

// This is acceptance bootstrap
use Symfony\Component\Filesystem\Path;

require_once Path::join((new \OxidEsales\Facts\Facts())->getShopRootPath(), 'source', 'bootstrap.php');

$helper = new \OxidEsales\Codeception\Module\FixturesHelper();
$helper->loadRuntimeFixtures(dirname(__FILE__) . '/../_data/fixtures.php');

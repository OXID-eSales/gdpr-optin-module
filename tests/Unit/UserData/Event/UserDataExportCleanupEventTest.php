<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Event;

use PHPUnit\Framework\TestCase;
use OxidEsales\GdprOptinModule\UserData\Event\UserDataExportCleanupEvent;

class UserDataExportCleanupEventTest extends TestCase
{
    public function testEventStoresFilePath(): void
    {
        $testFilePath = 'test/' . uniqid() . '.zip';

        $event = new UserDataExportCleanupEvent($testFilePath);

        $this->assertSame($testFilePath, $event->getFilePath());
    }
}

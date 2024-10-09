<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Event;

use org\bovigo\vfs\vfsStream;
use OxidEsales\GdprOptinModule\UserData\Event\UserDataExportCleanupSubscriber;
use OxidEsales\GdprOptinModule\UserData\Event\UserDataExportCleanupEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\Event;

class UserDataExportCleanupSubscriberTest extends TestCase
{
    public function testOnUserDataExportCleanupUnlinkedFile(): void
    {
        $fileName = uniqid() . '.zip';

        $vfsRoot = vfsStream::setup('root');
        $testFilePath = vfsStream::url('root/' . $fileName);

        $fileContents = uniqid();
        vfsStream::newFile($fileName, 0755)
            ->withContent($fileContents)
            ->at($vfsRoot);

        $this->assertTrue(file_exists($testFilePath));

        $userDataExportCleanupEventSpy = $this->createConfiguredMock(UserDataExportCleanupEvent::class, [
            'getFilePath' => $testFilePath,
        ]);

        $sut = new UserDataExportCleanupSubscriber();
        $sut->onUserDataExportCleanup(event: $userDataExportCleanupEventSpy);

        $this->assertFalse(file_exists($testFilePath));
    }

    public function testOnUserDataExportCleanupReturnsEvent(): void
    {
        $userDataExportCleanupEventSpy = $this->createStub(UserDataExportCleanupEvent::class);

        $sut = new UserDataExportCleanupSubscriber();

        $handlerResult = $sut->onUserDataExportCleanup(event: $userDataExportCleanupEventSpy);

        $this->assertInstanceOf(Event::class, $handlerResult);
    }

    public function testSubscriberSubscribesToCorrectEvent(): void
    {
        $this->assertArrayHasKey(
            UserDataExportCleanupEvent::class,
            UserDataExportCleanupSubscriber::getSubscribedEvents()
        );
    }
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use org\bovigo\vfs\vfsStream;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\GdprOptinModule\UserData\Event\UserDataExportCleanupEvent;
use OxidEsales\GdprOptinModule\UserData\Exception\UserDataFileDownloadException;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataFileDownloadService;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataFileDownloadServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserDataFileDownloadServiceTest extends TestCase
{
    public function testFilenameHeaderSet(): void
    {
        $fileName = uniqid() . '.zip';
        $tempDirectory = vfsStream::setup('root', null, [
            $fileName => uniqid()
        ]);
        $filePath = $tempDirectory->url() . '/' . $fileName;

        $shopUtilsSpy = $this->createPartialMock(Utils::class, ['setHeader', 'showMessageAndExit']);
        $shopUtilsSpy->expects($this->exactly(2))
            ->method('setHeader')
            ->willReturnCallback(function ($value) use ($fileName) {
                return match (1) {
                    preg_match("@Content-Type:application\/zip@mi", $value) => true,
                    preg_match("@Content-Disposition:attachment;filename={$fileName}@msi", $value) => true,
                    default => throw new \Exception("Header not expected: " . $value),
                };
            });

        $sut = $this->getSut(shopUtils: $shopUtilsSpy);

        $sut->downloadFile($filePath);
    }

    public function testCorrectFileContentShown(): void
    {
        $fileName = uniqid() . '.zip';
        $tempDirectory = vfsStream::setup('root', null, [
            $fileName => $fileContent = uniqid()
        ]);
        $filePath = $tempDirectory->url() . '/' . $fileName;

        $shopUtilsSpy = $this->createPartialMock(Utils::class, ['setHeader', 'showMessageAndExit']);
        $shopUtilsSpy->expects($this->atLeastOnce())
            ->method('showMessageAndExit')->with($fileContent);

        $sut = $this->getSut(shopUtils: $shopUtilsSpy);

        $sut->downloadFile($filePath);
    }

    public function testFileDoesntExist(): void
    {
        $tempDirectory = vfsStream::setup('root', null, []);
        $filePath = $tempDirectory->url() . '/' . uniqid();

        $eventDispatcherSpy = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherSpy->expects($this->never())
            ->method('dispatch');

        $sut = $this->getSut(eventDispatcher: $eventDispatcherSpy);

        $this->expectException(UserDataFileDownloadException::class);
        $sut->downloadFile($filePath);
    }

    public function testFileNotReadable(): void
    {
        $fileName = uniqid() . '.zip';
        $tempDirectory = vfsStream::setup('root', null, [
            $fileName => uniqid()
        ]);
        $filePath = $tempDirectory->url() . '/' . $fileName;
        chmod($filePath, 0000);

        $eventDispatcherSpy = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherSpy->expects($this->never())
            ->method('dispatch');

        $sut = $this->getSut(eventDispatcher: $eventDispatcherSpy);

        $this->expectException(UserDataFileDownloadException::class);
        $sut->downloadFile($filePath);
    }

    public function testEventDispatchTriggered(): void
    {
        $fileName = uniqid() . '.zip';
        $tempDirectory = vfsStream::setup('root', null, [
            $fileName => uniqid()
        ]);
        $filePath = $tempDirectory->url() . '/' . $fileName;

        $shopUtilsSpy = $this->createPartialMock(Utils::class, ['setHeader', 'showMessageAndExit']);
        $shopUtilsSpy->expects($this->atLeastOnce())
            ->method('showMessageAndExit');

        $eventDispatcherSpy = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherSpy->expects($this->atLeastOnce())
            ->method('dispatch')
            ->with($this->callback(function ($event) use ($filePath) {
                return $event instanceof UserDataExportCleanupEvent
                    && $event->getFilePath() === $filePath;
            }));

        $sut = $this->getSut(shopUtils: $shopUtilsSpy, eventDispatcher: $eventDispatcherSpy);

        $sut->downloadFile($filePath);
    }

    protected function getSut(
        ?Utils $shopUtils = null,
        ?EventDispatcherInterface $eventDispatcher = null,
    ): UserDataFileDownloadServiceInterface {
        $shopUtils ??= $this->createStub(Utils::class);
        $eventDispatcher ??= $this->createStub(EventDispatcherInterface::class);

        return new UserDataFileDownloadService(
            shopUtils: $shopUtils,
            eventDispatcher: $eventDispatcher,
        );
    }
}

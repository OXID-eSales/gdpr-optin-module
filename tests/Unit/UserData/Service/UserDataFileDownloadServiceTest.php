<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use org\bovigo\vfs\vfsStream;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\GdprOptinModule\UserData\Exception\UserDataFileDownloadException;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataFileDownloadService;
use PHPUnit\Framework\TestCase;

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

        $sut = new UserDataFileDownloadService(
            shopUtils: $shopUtilsSpy
        );
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

        $sut = new UserDataFileDownloadService(
            shopUtils: $shopUtilsSpy
        );
        $sut->downloadFile($filePath);
    }

    public function testFileDoesntExist(): void
    {
        $tempDirectory = vfsStream::setup('root', null, []);
        $filePath = $tempDirectory->url() . '/' . uniqid();

        $sut = new UserDataFileDownloadService(
            shopUtils: $this->createStub(Utils::class)
        );

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

        $sut = new UserDataFileDownloadService(
            shopUtils: $this->createStub(Utils::class)
        );

        $this->expectException(UserDataFileDownloadException::class);
        $sut->downloadFile($filePath);
    }
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\Service\ZipArchiveFactoryInterface;
use OxidEsales\GdprOptinModule\UserData\Service\ZipCreatorService;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class ZipCreatorServiceTest extends TestCase
{
    public function testZipCreationAddsFilesAndFinalizesTheArchive(): void
    {
        $fileName1 = uniqid() . '.txt';
        $fileContent1 = uniqid();

        $fileName2 = uniqid() . '.txt';
        $fileContent2 = uniqid();

        $zipArchiveSpy = $this->createMock(ZipArchive::class);
        $zipArchiveSpy->expects($this->exactly(2))
            ->method('addFromString')
            ->willReturnCallback(
                function ($fileName, $fileContent) use ($fileName1, $fileContent1, $fileName2, $fileContent2) {
                    return match ([$fileName, $fileContent]) {
                        [$fileName1, $fileContent1] => true,
                        [$fileName2, $fileContent2] => true,
                        default => throw new \Exception("Unexpected arguments passed to addFromString")
                    };
                }
            );

        $zipArchiveSpy
            ->expects($this->once())
            ->method('close');

        $zipFileName = 'output/test.zip';
        $zipArchiveFactoryMock = $this->createMock(ZipArchiveFactoryInterface::class);
        $zipArchiveFactoryMock
            ->method('create')
            ->with($zipFileName)
            ->willReturn($zipArchiveSpy);

        $sut = new ZipCreatorService(zipArchiveFactory: $zipArchiveFactoryMock);

        $resultFileMock1 = $this->createConfiguredMock(ResultFileInterface::class, [
            'getFileName' => $fileName1,
            'getContent' => $fileContent1,
        ]);

        $resultFileMock2 = $this->createConfiguredMock(ResultFileInterface::class, [
            'getFileName' => $fileName2,
            'getContent' => $fileContent2,
        ]);

        $sut->createZip(files: [$resultFileMock1, $resultFileMock2], outputFilePath: $zipFileName);
    }
}

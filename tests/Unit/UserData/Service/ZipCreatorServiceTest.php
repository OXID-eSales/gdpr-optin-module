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
    public function testZipCreation(): void
    {
        $fileName = uniqid();
        $fileContent = uniqid();

        $zipArchiveMock = $this->createMock(ZipArchive::class);
        $zipArchiveMock
            ->method('addFromString')
            ->with($fileName, $fileContent)
            ->willReturn(true);

        $zipArchiveMock
            ->method('close')
            ->willReturn(true);

        $zipFileName = 'output/test.zip';
        $zipArchiveFactoryMock = $this->createMock(ZipArchiveFactoryInterface::class);
        $zipArchiveFactoryMock
            ->method('create')
            ->with($zipFileName)
            ->willReturn($zipArchiveMock);

        $sut = new ZipCreatorService(zipArchiveFactory: $zipArchiveFactoryMock);

        $resultFileMock = $this->createConfiguredMock(ResultFileInterface::class, [
            'getFileName' => $fileName,
            'getContent' => $fileContent,
        ]);

        $sut->createZip(files: [$resultFileMock], outputFilePath: $zipFileName);
    }
}

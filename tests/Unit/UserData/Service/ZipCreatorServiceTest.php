<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use org\bovigo\vfs\vfsStream;
use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\Exception\ZipCreationException;
use OxidEsales\GdprOptinModule\UserData\Service\ZipCreatorService;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class ZipCreatorServiceTest extends TestCase
{
    public function testZipCreation(): void
    {
        vfsStream::setup('outputDir');
        $outputZipFilePath = vfsStream::url('outputDir/test_zip_file.zip');
        $fileName = uniqid();
        $fileContent = uniqid();

        $resultFileInterStub = $this->createConfiguredMock(ResultFileInterface::class, [
            'getFileName' => $fileName,
            'getContent' => $fileContent,
        ]);

        $zipArchiveSpy = $this->createMock(ZipArchive::class);
        $zipArchiveSpy
            ->method('open')
            ->with($outputZipFilePath, ZipArchive::CREATE)
            ->willReturn(true);

        $zipArchiveSpy
            ->method('addFromString')
            ->with($fileName, $fileContent);

        $sut = new ZipCreatorService(zipArchive: $zipArchiveSpy);
        $sut->createZip(files: [$resultFileInterStub], outputFilePath: $outputZipFilePath);

        $zipArchiveSpy
            ->method('close');
    }

    public function testCreateZipThrowsExceptionWhenUnableToOpen(): void
    {
        vfsStream::setup('outputDir');
        $outputZipFilePath = vfsStream::url('outputDir/output.zip');

        $this->expectException(ZipCreationException::class);
        $this->expectExceptionMessage("Unable to open zip file at {$outputZipFilePath}");

        $zipArchiveMock = $this->createMock(ZipArchive::class);
        $zipArchiveMock
            ->method('open')
            ->with($outputZipFilePath, ZipArchive::CREATE)
            ->willReturn(false);


        $sut = new ZipCreatorService(zipArchive: $zipArchiveMock);
        $sut->createZip([], $outputZipFilePath);
    }
}

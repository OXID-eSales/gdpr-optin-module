<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use org\bovigo\vfs\vfsStream;
use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataCollectionServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportService;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataFileDownloadServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\ZipCreatorServiceInterface;
use PHPUnit\Framework\TestCase;

class UserDataExportServiceTest extends TestCase
{
    public function testZipCreationServiceTriggeredWithCorrectlyProcessedData(): void
    {
        $userId = uniqid();

        vfsStream::setup('outputDir');
        $userDataZipFilePath = vfsStream::url('outputDir');
        $expectedOutputFilePath = $userDataZipFilePath . '/' . $userId . '.zip';

        $filesListExample = [
            $this->createStub(ResultFileInterface::class),
            $this->createStub(ResultFileInterface::class),
        ];

        $userDataCollectionServiceMock = $this->createMock(UserDataCollectionServiceInterface::class);
        $userDataCollectionServiceMock->method('getUserDataAsFilesList')
            ->with($userId)
            ->willReturn($filesListExample);

        $zipCreatorServiceSpy = $this->createMock(ZipCreatorServiceInterface::class);
        $zipCreatorServiceSpy->expects($this->once())
            ->method('createZip')
            ->with($filesListExample, $expectedOutputFilePath);

        $fileDownloadServiceSpy = $this->createMock(UserDataFileDownloadServiceInterface::class);
        $fileDownloadServiceSpy
            ->expects($this->once())
            ->method('downloadFile')
            ->with($expectedOutputFilePath);

        $sut = $this->getSut(
            userDataCollectionService: $userDataCollectionServiceMock,
            zipCreatorService: $zipCreatorServiceSpy,
            userDataFileDownloadService: $fileDownloadServiceSpy,
            userDataZipFilePath: $userDataZipFilePath
        );

        $sut->exportUserData(userId: $userId);
    }

    public function getSut(
        ?UserDataCollectionServiceInterface $userDataCollectionService,
        ?ZipCreatorServiceInterface $zipCreatorService,
        ?UserDataFileDownloadServiceInterface $userDataFileDownloadService,
        ?string $userDataZipFilePath
    ): UserDataExportServiceInterface {
        $userDataCollectionService ??= $this->createStub(UserDataCollectionServiceInterface::class);
        $zipCreatorService ??= $this->createStub(ZipCreatorServiceInterface::class);
        $userDataFileDownloadService ??= $this->createStub(UserDataFileDownloadServiceInterface::class);
        $userDataZipFilePath ??= 'tmp/UserData';

        return new UserDataExportService(
            userDataCollectionService: $userDataCollectionService,
            zipCreatorService: $zipCreatorService,
            userDataFileDownloadService: $userDataFileDownloadService,
            userDataZipFilePath: $userDataZipFilePath
        );
    }
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataCollectionServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportService;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\ZipCreatorServiceInterface;
use PHPUnit\Framework\TestCase;

class UserDataExportTest extends TestCase
{
    public function testZipCreationServiceTriggeredWithCorrectlyProcessedData(): void
    {
        $userId = uniqid();
        $outputZipFilePath = uniqid();

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
            ->with($filesListExample, $outputZipFilePath);

        $sut = $this->getSut(
            userDataCollectionService: $userDataCollectionServiceMock,
            zipCreatorService: $zipCreatorServiceSpy,
        );

        $sut->exportUserData($userId, $outputZipFilePath);
    }

    public function getSut(
        ?UserDataCollectionServiceInterface $userDataCollectionService,
        ?ZipCreatorServiceInterface $zipCreatorService,
    ): UserDataExportServiceInterface {
        $userDataCollectionService ??= $this->createStub(UserDataCollectionServiceInterface::class);
        $zipCreatorService ??= $this->createStub(ZipCreatorServiceInterface::class);

        return new UserDataExportService(
            userDataCollectionService: $userDataCollectionService,
            zipCreatorService: $zipCreatorService,
        );
    }
}

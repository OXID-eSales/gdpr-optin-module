<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Controller;

use OxidEsales\GdprOptinModule\UserData\Controller\UserDataExportController;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserDataExportControllerTest extends TestCase
{
    public function testExportUserData(): void
    {
        $userId = uniqid();

        $userDataExportServiceSpy = $this->createMock(UserDataExportServiceInterface::class);
        $userDataExportServiceSpy
            ->expects($this->once())
            ->method('exportUserData')
            ->with($userId);

        $sut = $this->getSut(
            userDataExportService: $userDataExportServiceSpy
        );

        $sut->method('getEditObjectId')->willReturn($userId);
        $sut->exportUserData();
    }

    private function getSut(
        UserDataExportServiceInterface $userDataExportService = null
    ): UserDataExportController&MockObject {
        $sut = $this->createPartialMock(UserDataExportController::class, ['getService', 'getEditObjectId']);

        $userDataExportService ??= $this->createStub(UserDataExportServiceInterface::class);

        $sut->method('getService')->willReturnMap([
            [UserDataExportServiceInterface::class, $userDataExportService]
        ]);

        return $sut;
    }
}

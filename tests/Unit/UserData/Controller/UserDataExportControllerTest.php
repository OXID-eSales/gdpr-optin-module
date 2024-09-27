<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Controller;

use OxidEsales\GdprOptinModule\UserData\Controller\UserDataExportController;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportServiceInterface;
use PHPUnit\Framework\TestCase;

class UserDataExportControllerTest extends TestCase
{
    public function testExportUserData(): void
    {
        $userId = uniqid();
        $userDataExportServiceSpy = $this->createMock(UserDataExportServiceInterface::class);
        $userDataExportServiceSpy
            ->method('exportUserData')
            ->with($userId);

        $sut = $this->getSut(userId: $userId, userDataExportService: $userDataExportServiceSpy,);
        $sut->exportUserData();
    }

    private function getSut(
        string $userId,
        UserDataExportServiceInterface $userDataExportService = null
    ): UserDataExportController {
        $sut = $this->createPartialMock(UserDataExportController::class, ['getService', 'getEditObjectId']);

        $sut->method('getService')->willReturn($userDataExportService);
        $sut->method('getEditObjectId')->willReturn($userId);

        return $sut;
    }
}

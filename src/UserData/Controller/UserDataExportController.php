<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Controller;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataExportServiceInterface;

class UserDataExportController extends AdminController
{
    public function exportUserData(): void
    {
        $userId = $this->getEditObjectId();
        $userDataExportService = $this->getService(UserDataExportServiceInterface::class);
        $userDataExportService->exportUserData(userId: $userId);
    }
}

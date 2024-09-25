<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\Eshop\Core\Utils;
use OxidEsales\GdprOptinModule\UserData\Exception\UserDataFileDownloadException;

class UserDataFileDownloadService implements UserDataFileDownloadServiceInterface
{
    public function __construct(
        private Utils $shopUtils
    ) {
    }

    public function downloadFile(string $filePath): void
    {
        $this->validateAccess($filePath);

        $fileName = basename($filePath);

        $this->shopUtils->setHeader('Content-Type:application/zip');
        $this->shopUtils->setHeader('Content-Disposition:attachment;filename=' . $fileName);

        /** @var string $fileContent */
        $fileContent = file_get_contents($filePath);
        $this->shopUtils->showMessageAndExit($fileContent);
    }

    private function validateAccess(string $filePath): void
    {
        if (!is_file($filePath)) {
            throw new UserDataFileDownloadException('File does not exist');
        }

        if (!is_readable($filePath)) {
            throw new UserDataFileDownloadException('File is not readable');
        }
    }
}

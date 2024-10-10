<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\Eshop\Core\Utils;
use OxidEsales\GdprOptinModule\UserData\Event\UserDataExportCleanupEvent;
use OxidEsales\GdprOptinModule\UserData\Exception\UserDataFileDownloadException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserDataFileDownloadService implements UserDataFileDownloadServiceInterface
{
    public function __construct(
        private Utils $shopUtils,
        private EventDispatcherInterface $eventDispatcher
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

        $event = new UserDataExportCleanupEvent($filePath);
        $this->eventDispatcher->dispatch(event: $event);

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

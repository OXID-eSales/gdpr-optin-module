<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

class UserDataExportService implements UserDataExportServiceInterface
{
    public function __construct(
        private UserDataCollectionServiceInterface $userDataCollectionService,
        private ZipCreatorServiceInterface $zipCreatorService,
        private UserDataFileDownloadServiceInterface $userDataFileDownloadService,
        private string $userDataZipFilePath
    ) {
    }

    public function exportUserData(string $userId): void
    {
        $outputZipFilePath  = $this->userDataZipFilePath . '/' . $userId . '.zip';
        $serializedFiles = $this->userDataCollectionService->getUserDataAsFilesList(userId: $userId);
        $this->zipCreatorService->createZip(files: $serializedFiles, outputFilePath: $outputZipFilePath);
        $this->userDataFileDownloadService->downloadFile(filePath: $outputZipFilePath);
    }
}

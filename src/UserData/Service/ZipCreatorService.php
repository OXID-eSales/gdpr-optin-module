<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\Exception\ZipCreationException;
use ZipArchive;

class ZipCreatorService implements ZipCreatorServiceInterface
{
    public function __construct(
        private ZipArchive $zipArchive
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createZip(array $files, string $outputFilePath): void
    {
        if ($this->zipArchive->open($outputFilePath, ZipArchive::CREATE) !== true) {
            throw new ZipCreationException("Unable to open zip file at {$outputFilePath}");
        }

        foreach ($files as $file) {
            $fileName = $file->getFileName();
            $fileContent = $file->getContent();
            $this->zipArchive->addFromString($fileName, $fileContent);
        }

        $this->zipArchive->close();
    }
}

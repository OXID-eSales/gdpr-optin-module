<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

class ZipCreatorService implements ZipCreatorServiceInterface
{
    public function __construct(
        private ZipArchiveFactoryInterface $zipArchiveFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createZip(array $files, string $outputFilePath): void
    {
        $zipArchive = $this->zipArchiveFactory->create($outputFilePath);

        foreach ($files as $file) {
            $zipArchive->addFromString($file->getFileName(), $file->getContent());
        }

        $zipArchive->close();
    }
}

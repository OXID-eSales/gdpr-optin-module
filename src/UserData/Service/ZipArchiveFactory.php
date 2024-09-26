<?php

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\Exception\ZipCreationException;
use ZipArchive;

class ZipArchiveFactory implements ZipArchiveFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(string $filePath): ZipArchive
    {
        $zipArchive = new ZipArchive();
        if ($zipArchive->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new ZipCreationException("Unable to open zip file at {$filePath}");
        }

        return $zipArchive;
    }
}

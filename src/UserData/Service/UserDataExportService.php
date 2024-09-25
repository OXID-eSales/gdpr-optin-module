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
        private CollectionAggregationServiceInterface $collectionAggregationService,
        private CollectionSerializerServiceInterface $collectionSerializerService,
        private ZipCreatorInterface $zipCreatorService,
    ) {
    }

    /**
     * collects data from CollectionAggregationService as array of Collections
     * iterate through collections and serialize them to ResultFileInterfaces array
     * give this array of ResultFileInterfaces to the ZipCreatorInterface
     */
    public function exportUserData(string $userId, string $outputZipFilePath): void
    {
        $tableDataCollections = $this->collectionAggregationService->collectUserData($userId);

        $serializedFiles = [];
        foreach ($tableDataCollections as $oneTableDataCollection) {
            $serializedFiles[] = $this->collectionSerializerService->serializeCollection($oneTableDataCollection);
        }

        $this->zipCreatorService->createZip($serializedFiles, $outputZipFilePath);
    }
}

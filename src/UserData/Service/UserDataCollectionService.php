<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

class UserDataCollectionService implements UserDataCollectionServiceInterface
{
    public function __construct(
        private CollectionAggregationServiceInterface $collectionAggregationService,
        private CollectionSerializerServiceInterface $collectionSerializerService,
    ) {
    }

    public function getUserDataAsFilesList(string $userId): array
    {
        $tableDataCollections = $this->collectionAggregationService->collectUserData($userId);

        $serializedFiles = [];
        foreach ($tableDataCollections as $oneTableDataCollection) {
            $serializedFiles[] = $this->collectionSerializerService->serializeCollection($oneTableDataCollection);
        }

        return $serializedFiles;
    }
}

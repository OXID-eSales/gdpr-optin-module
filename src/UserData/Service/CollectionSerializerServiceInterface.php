<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollectionInterface;
use OxidEsales\GdprOptinModule\UserData\Exception\JsonSerializationException;

interface CollectionSerializerServiceInterface
{
    /**
     * @throws JsonSerializationException
     */
    public function serializeCollection(TableDataCollectionInterface $data): ResultFileInterface;
}

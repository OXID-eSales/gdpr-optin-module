<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFile;
use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\DataType\TableCollectionInterface;

class JsonCollectionSerializerService implements CollectionSerializerServiceInterface
{
    public function serializeCollection(TableCollectionInterface $data): ResultFileInterface
    {
        return new ResultFile($data->getCollectionName(), json_encode($data->getCollection()));
    }
}

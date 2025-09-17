<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;

interface UserDataCollectionServiceInterface
{
    /**
     * @return array<ResultFileInterface>
     */
    public function getUserDataAsFilesList(string $userId): array;
}

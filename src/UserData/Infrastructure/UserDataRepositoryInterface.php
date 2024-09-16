<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

interface UserDataRepositoryInterface
{
    public function getDataFromTable(string $table, string $columnName, string $columnValue): array;
}

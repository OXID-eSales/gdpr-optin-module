<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

interface DataSelectorInterface
{
    public function getDataForColumnValue(string $columnValue): array;
}

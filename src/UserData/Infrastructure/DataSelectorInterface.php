<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

interface DataSelectorInterface
{
    public function getCollection(): string;

    public function getSelectionTable(): string;

    /**
     * @return array<array<string, string>>
     */
    public function getDataForColumnValue(string $columnValue): array;
}

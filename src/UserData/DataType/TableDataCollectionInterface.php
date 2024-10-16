<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\DataType;

interface TableDataCollectionInterface
{
    public function getCollectionName(): string;

    /**
     * @return array<string, array<array<string, string>>>
     */
    public function getCollection(): array;
}

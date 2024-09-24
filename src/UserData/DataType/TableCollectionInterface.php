<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\DataType;

interface TableCollectionInterface
{
    public function getCollectionName(): string;

    /**
     * @return array<array<string, string>>
     */
    public function getCollection(): array;
}
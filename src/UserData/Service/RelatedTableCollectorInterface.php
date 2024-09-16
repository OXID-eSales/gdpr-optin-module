<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

interface RelatedTableCollectorInterface
{
    public function collect(string $id): array;
    public function getTableName(): string;
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\DataType;

interface ResultFileInterface
{
    public function getFilename(): string;

    public function getContent(): string;
}
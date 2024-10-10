<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\DataType;

class ResultFile implements ResultFileInterface
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $content
    ) {
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

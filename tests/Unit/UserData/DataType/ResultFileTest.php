<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GdprOptinModule\UserData\DataType\ResultFile;

final class ResultFileTest extends TestCase
{
    public function testResultFileDataType(): void
    {
        $expectedFileName = uniqid();
        $expectedFileContent = uniqid();

        $sut = new ResultFile(fileName: $expectedFileName, content: $expectedFileContent);

        $this->assertSame($expectedFileName, $sut->getFilename());
        $this->assertSame($expectedFileContent, $sut->getContent());
    }
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\DataType;

use OxidEsales\GdprOptinModule\UserData\DataType\TableCollection;
use PHPUnit\Framework\TestCase;

class TableCollectionTest extends TestCase
{
    public function testTableCollection(): void
    {
        $collectionName = uniqid();
        $dataCollection = [
            [
                uniqid() => uniqid(),
                uniqid() => uniqid(),
            ],
            [
                uniqid() => uniqid(),
                uniqid() => uniqid(),
            ],
        ];

        $sut = new TableCollection($collectionName, $dataCollection);

        $this->assertEquals($collectionName, $sut->getCollectionName());
        $this->assertEquals($dataCollection, $sut->getCollection());
    }
}

<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\TableCollectionInterface;
use OxidEsales\GdprOptinModule\UserData\Exception\JsonSerializationException;
use OxidEsales\GdprOptinModule\UserData\Service\JsonCollectionSerializerService;
use PHPUnit\Framework\TestCase;

final class JsonCollectionSerializerServiceTest extends TestCase
{
    public function testSerializeCollection(): void
    {
        $expectedCollectionName = uniqid();
        $expectedCollection = [
            ['OXID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()],
            ['OXID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()]
        ];

        $tableCollectionMock = $this->createConfiguredMock(TableCollectionInterface::class, [
            'getCollection' => $expectedCollection,
            'getCollectionName' => $expectedCollectionName,
        ]);

        $sut = new JsonCollectionSerializerService();

        $actualResult = $sut->serializeCollection($tableCollectionMock);

        $this->assertSame($expectedCollectionName, $actualResult->getFileName());
        $this->assertSame(json_encode($expectedCollection), $actualResult->getContent());
    }

    public function testSerializeCollectionThrowsExceptionOnJsonError()
    {
        $invalidString = "\xB1\x31\x32";
        $invalidCollection = [
            ['someColum1' => uniqid(), 'someColum2' => $invalidString]
        ];

        $tableCollectionMock = $this->createConfiguredMock(TableCollectionInterface::class, [
            'getCollection' => $invalidCollection,
            'getCollectionName' => uniqid(),
        ]);

        $sut = new JsonCollectionSerializerService();

        $this->expectException(JsonSerializationException::class);

        $sut->serializeCollection($tableCollectionMock);
    }
}

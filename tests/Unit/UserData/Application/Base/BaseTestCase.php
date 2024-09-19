<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base;

use OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepositoryInterface;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function createUserDataRepositoryMock(
        array $expectedUserData,
        string $method,
        array $withParams,
    ): UserDataRepositoryInterface {
        $userDataRepositoryMock = $this->createMock(UserDataRepositoryInterface::class);
        $userDataRepositoryMock->method($method)
            ->with(...$withParams)
            ->willReturn($expectedUserData);

        return $userDataRepositoryMock;
    }

    protected function expectedUserData(): array
    {
        return [
            ['OXID' => '1', 'OXUSERID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()],
            ['OXID' => '2', 'OXUSERID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()]
        ];
    }
}

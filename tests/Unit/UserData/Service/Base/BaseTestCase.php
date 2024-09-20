<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service\Base;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function expectedUserData(): array
    {
        return [
            ['OXID' => uniqid(), 'OXUSERID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()],
            ['OXID' => uniqid(), 'OXUSERID' => uniqid(), 'OXDELFNAME' => uniqid(), 'OXDELLNAME' => uniqid()]
        ];
    }
}

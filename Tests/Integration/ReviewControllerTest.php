<?php
/**
 * This file is part of OXID eSales GDPR opt-in module.
 *
 * OXID eSales GDPR opt-in module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales GDPR opt-in module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales GDPR opt-in module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2018
 */

namespace OxidEsales\GdprOptinModule\Tests\Integration;

/**
 * Class ReviewControllerTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class ReviewControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test validation error appears if needed
     */
    public function testSendError()
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, true);
        $this->assertFalse($controller->saveReview());
    }

    /**
     * Test validation error appears if needed
     */
    public function testSendNotError()
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, false);
        $this->assertNull($controller->saveReview());
    }

    /**
     * Test if validation is required.
     *
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired($configValue, $expected)
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->assertSame($expected, $controller->isReviewOptInValidationRequired());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInValidationRequired()
    {
        return [
            'required' => [true, true],
            'not-required' => [false, false]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderValidateOptIn
     */
    public function testValidateOptIn($configValue, $checkboxStatus, $expectedValue)
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->validateOptIn());
    }

    /**
     * @return array
     */
    public function dataProviderValidateOptIn()
    {
        return [
            'required-checked' => [true, 1, true],
            'required-not-checked' => [true, 0, false],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, true],
            'not-required-not-checked' => [false, 0, true],
            'not-required-not-exits' => [false, null, true]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError($configValue, $checkboxStatus, $expectedValue)
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInError()
    {
        return [
            'required-checked' => [true, 1, false],
            'required-not-checked' => [true, 0, true],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, false],
            'not-required-not-checked' => [false, 0, false],
            'not-required-not-exits' => [false, null, false]
        ];
    }
}

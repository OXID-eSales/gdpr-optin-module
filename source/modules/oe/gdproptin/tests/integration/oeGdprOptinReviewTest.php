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

class oeGdprOptinReviewTest extends OxidTestCase
{
    /**
     * Test validation error appears if needed
     */
    public function testSendError()
    {
        $controller = oxNew("Review");
        oxRegistry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, true);
        $this->assertFalse($controller->saveReview());
    }

    /**
     * Test validation error appears if needed
     */
    public function testSendNotError()
    {
        $controller = oxNew("Review");
        oxRegistry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, false);
        $this->assertNull($controller->saveReview());
    }

    /**
     * Test if validation is required.
     *
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired($configValue, $expected)
    {
        $controller = oxNew("Review");
        oxRegistry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->assertSame($expected, $controller->isReviewOptInValidationRequired());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInValidationRequired()
    {
        return array(
            'required' => array(true, true),
            'not-required' => array(false, false),
        );
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderValidateOptIn
     */
    public function testValidateOptIn($configValue, $checkboxStatus, $expectedValue)
    {
        $controller = oxNew("Review");
        oxRegistry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->getConfig()->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->validateOptIn());
    }

    /**
     * @return array
     */
    public function dataProviderValidateOptIn()
    {
        return array(
            'required-checked' => array(true, 1, true),
            'required-not-checked' => array(true, 0, false),
            'required-not-exist' => array(true, null, false),
            'not-required-checked' => array(false, 1, true),
            'not-required-not-checked' => array(false, 0, true),
            'not-required-not-exits' => array(false, null, true)
        );
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError($configValue, $checkboxStatus, $expectedValue)
    {
        $controller = oxNew("Review");
        oxRegistry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->getConfig()->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInError()
    {
        return array(
            'required-checked' => array(true, 1, false),
            'required-not-checked' => array(true, 0, true),
            'required-not-exist' => array(true, null, false),
            'not-required-checked' => array(false, 1, false),
            'not-required-not-checked' => array(false, 0, false),
            'not-required-not-exits' => array(false, null, false)
        );
    }
}

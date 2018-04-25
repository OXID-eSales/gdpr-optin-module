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

class oeGdprOptinOxcmp_userTest extends OxidTestCase
{
    const TEST_USER_ID = '_gdprtest';

    /**
     * Test set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->createTestUser();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->cleanUpTable('oxuser', 'oxid');

        parent::tearDown();
    }

    /**
     * @return array
     */
    public function providerDeliveryAddressOptin()
    {
        //Optin will only be required on changed or new address.
        $addAddress = array('oxaddressid' => '-1');
        $changedAddress = array('oxaddressid' => 'someuniqueid', 'oegdproptin_changeDelAddress' => '1');

        return array(
            'optin_true_checkbox_true_show_true_new' => array(true, true, 'assertFalse', true, $addAddress),
            'optin_true_checkbox_true_show_true_change' => array(true, true, 'assertFalse', true, $changedAddress),
            'optin_true_checkbox_true_show_true' => array(true, true, 'assertFalse', true, array()),

            'optin_true_checkbox_false_show_true_new' => array(true, false, 'assertTrue', true, $addAddress),
            'optin_true_checkbox_false_show_true_change' => array(true, false, 'assertTrue', true, $changedAddress),
            'optin_true_checkbox_false_show_true' => array(true, false, 'assertTrue', true, array()),

            'optin_false_checkbox_true_show_true' => array(false, true, 'assertFalse', true, array()),
            'optin_false_checkbox_false_show_true' => array(false, false, 'assertFalse', true, array()),

            'optin_true_checkbox_true_show_false' => array(true, true, 'assertFalse', false, array()),
            'optin_true_checkbox_false_show_false' => array(true, false, 'assertFalse', false, array()),

            'optin_false_checkbox_true_show_false' => array(false, true, 'assertFalse', false, array()),
            'optin_false_checkbox_false_show_false' => array(false, false, 'assertFalse', false, array()),
        );
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool   $blOeGdprOptinDeliveryAddress
     * @param bool   $checkboxChecked
     * @param string $sAssertDisplayExc
     * @param bool   $showShip
     * @param array  $parameters
     */
    public function testDeliveryAddressOptinValidationCheckoutUser($blOeGdprOptinDeliveryAddress, $checkboxChecked, $sAssertDisplayExc, $showShip, $parameters)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $blOeGdprOptinDeliveryAddress);

        $parameters['oegdproptin_deliveryaddress'] = (int) $checkboxChecked;
        $parameters['blshowshipaddress'] = (int) $showShip;
        $this->addRequestParameters($parameters);

        $oCmpUser = oxNew('oxcmp_user');
        $oCmpUser->changeuser();

        $aDisplayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$sAssertDisplayExc(array_key_exists('oegdproptin_deliveryaddress', $aDisplayErrors));
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool   $blOeGdprOptinDeliveryAddress
     * @param bool   $checkboxChecked
     * @param string $sAssertDisplayExc
     * @param bool   $showShip
     * @param array  $parameters
     */
    public function testDeliveryAddressOptinValidationAccountUser($blOeGdprOptinDeliveryAddress, $checkboxChecked, $sAssertDisplayExc, $showShip, $parameters)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $blOeGdprOptinDeliveryAddress);

        $parameters['oegdproptin_deliveryaddress'] = (int) $checkboxChecked;
        $parameters['blshowshipaddress'] = (int) $showShip;
        $this->addRequestParameters($parameters);

        $oCmpUser = oxNew('oxcmp_user');
        $oCmpUser->changeuser_testvalues();

        $aDisplayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$sAssertDisplayExc(array_key_exists('oegdproptin_deliveryaddress', $aDisplayErrors));
    }


    /**
     * @return array
     */
    public function providerInvoiceAddressOptin()
    {
        //Optin will be required on changed invoice address
        $changedAddress = array('oegdproptin_changeInvAddress' => '1');

        return array(
            'optin_true_checkbox_true_change' => array(true, true, 'assertFalse', $changedAddress),
            'optin_true_checkbox_true_no_change' => array(true, true, 'assertFalse', array()),

            'optin_true_checkbox_false_change' => array(true, false, 'assertTrue', $changedAddress),
            'optin_true_checkbox_false_no_change' => array(true, false, 'assertFalse', array()),

            'optin_false_checkbox_false_change' => array(false, false, 'assertFalse', $changedAddress),
            'optin_false_checkbox_false_no_change' => array(false, false, 'assertFalse', array()),

            'optin_false_checkbox_true_change' => array(false, true, 'assertFalse', $changedAddress),
            'optin_false_checkbox_true_no_change' => array(false, true, 'assertFalse', array()),
        );
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerInvoiceAddressOptin
     *
     * @param bool   $requireGdprOptinInvoiceAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param array  $parameters
     */
    public function testInvoiceAddressOptinValidationCheckoutUser($requireGdprOptinInvoiceAddress, $checkboxChecked, $assertDisplayExc, $parameters)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $requireGdprOptinInvoiceAddress);

        $parameters['oegdproptin_invoiceaddress'] = (int) $checkboxChecked;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew('oxcmp_user');
        $cmpUser->changeuser();

        $displayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_invoiceaddress', $displayErrors));
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerInvoiceAddressOptin
     *
     * @param bool   $requireGdprOptinInvoiceAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param array  $parameters
     */
    public function testInvoiceAddressOptinValidationAccountUser($requireGdprOptinInvoiceAddress, $checkboxChecked, $assertDisplayExc, $parameters)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $requireGdprOptinInvoiceAddress);

        $parameters['oegdproptin_invoiceaddress'] = (int) $checkboxChecked;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew('oxcmp_user');
        $cmpUser->changeuser_testvalues();

        $displayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_invoiceaddress', $displayErrors));
    }

    /**
     * @return array
     */
    public function providerUserRegistrationOptin()
    {
        return array(
            'enable_true_optin_true_register' => array(true, true, 'assertFalse'),
            'enable_true_optin_false_register' => array(true, false, 'assertTrue'),
            'enable_false_optin_true_register' => array(false, true, 'assertFalse'),
            'enable_false_optin_false_register' => array(false, false, 'assertFalse')
        );
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerUserRegistrationOptin
     *
     * @param bool   $oeGdprUserRegistrationAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param string $parent
     */
    public function testUserRegistrationOptinValidation($oeGdprUserRegistrationAddress, $checkboxChecked, $assertDisplayExc)
    {
        oxRegistry::getSession()->setUser(null);
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $oeGdprUserRegistrationAddress);

        $parameters = array('oegdproptin_userregistration' => (int) $checkboxChecked,
                            'option' => 3);
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew('oxcmp_user');
        $parentView = oxNew('register');
        $cmpUser->setParent($parentView);
        $cmpUser->createUser();

        $displayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_userregistration', $displayErrors));
    }

    /**
     * @return array
     */
    public function providerUserCheckoutRegistrationOptin()
    {
        return array(
             'enable_true_optin_true_guestbuy' => array(true, true, 'assertFalse', 1),
             'enable_true_optin_false_guestbuy' => array(true, false, 'assertFalse', 1),
             'enable_false_optin_true_guestbuy' => array(false, true, 'assertFalse', 1),
             'enable_false_optin_false_guestbuy' => array(false, false, 'assertFalse', 1),
             'enable_true_optin_true_createuser' => array(true, true, 'assertFalse', 3),
             'enable_true_optin_false_createuser' => array(true, false, 'assertTrue', 3),
             'enable_false_optin_true_createuser' => array(false, true, 'assertFalse', 3),
             'enable_false_optin_false_createuser' => array(false, false, 'assertFalse', 3),
        );
    }
    /**
     * Test checkbox validation.
     *
     * @dataProvider  providerUserCheckoutRegistrationOptin
     *
     * @param bool   $oeGdprUserRegistrationAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param int    $option (1 = guest buy/no optin, 3 = create account)
     */
    public function testUserRegistrationOptinValidationCheckoutUser($oeGdprUserRegistrationAddress, $checkboxChecked, $assertDisplayExc, $option)
    {
        oxRegistry::getSession()->setUser(null);
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $oeGdprUserRegistrationAddress);

        $parameters = array('oegdproptin_userregistration' => (int) $checkboxChecked,
                            'option' => $option);
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew('oxcmp_user');
        $parentView = oxNew('user');
        $cmpUser->setParent($parentView);
        $cmpUser->createUser();

        $displayErrors = oxRegistry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_userregistration', $displayErrors));
    }

    /**
     * Create a test user.
     */
    private function createTestUser()
    {
        $user = oxNew('oxUser');
        $user->setId(self::TEST_USER_ID);
        $user->assign(
            array(
                'oxfname'             => 'Max',
                'oxlname'             => 'Mustermann',
                'oxusername'          => 'gdpruser@oxid.de',
                'oxpassword'          => md5('agent'),
                'oxactive'            => 1,
                'oxshopid'            => 1,
                'oxcountryid'         => 'a7c40f631fc920687.20179984',
                'oxboni'              => '600',
                'oxstreet'            => 'Teststreet',
                'oxstreetnr'          => '101',
                'oxcity'              => 'Hamburg',
                'oxzip'               => '22769'
            )
        );
        $user->save();

        //Ensure we have it in session and as active user
        $this->ensureActiveUser();
    }

    /**
     * Make sure we have the test user as active user.
     */
    private function ensureActiveUser()
    {
        $this->setSessionParam('usr', self::TEST_USER_ID);
        $this->setSessionParam('auth', self::TEST_USER_ID);

        $user = oxNew('oxUser');
        $user->load(self::TEST_USER_ID);
        oxRegistry::getSession()->setUser($user);
        $user->setUser($user);
        $this->assertTrue($user->loadActiveUser());
    }

    /**
     * Test helper for setting requets parameters.
     *
     * @param array $parameters
     */
    private function addRequestParameters($additionalParameters = array())
    {
        $address = 'a:13:{s:16:"oxaddress__oxsal";s:2:"MR";s:18:"oxaddress__oxfname";s:4:"Moxi";' .
                   's:18:"oxaddress__oxlname";s:6:"Muster";s:20:"oxaddress__oxcompany";s:0:"";' .
                   's:20:"oxaddress__oxaddinfo";s:0:"";s:19:"oxaddress__oxstreet";s:10:"Nicestreet";' .
                   's:21:"oxaddress__oxstreetnr";s:3:"666";s:16:"oxaddress__oxzip";s:5:"12345";' .
                   's:17:"oxaddress__oxcity";s:9:"Somewhere";s:22:"oxaddress__oxcountryid";' .
                   's:26:"a7c40f631fc920687.20179984";s:20:"oxaddress__oxstateid";s:0:"";' .
                   's:16:"oxaddress__oxfon";s:0:"";s:16:"oxaddress__oxfax";s:0:"";}';

        $deliveryAddress = unserialize($address);
        $parameters = array('deladr' => $deliveryAddress,
                            'stoken' => oxRegistry::getSession()->getSessionChallengeToken());

        $parameters = array_merge($parameters, $additionalParameters);

        foreach ($parameters as $key => $value) {
            $this->getConfig()->setRequestParameter($key, $value);
        }
    }
}

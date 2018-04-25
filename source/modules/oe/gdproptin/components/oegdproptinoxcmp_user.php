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

/**
 * Class oeGdprOptinOxcmp_user.
 * Extends oxcmp_user.
 *
 * @see oxcmp_user
 */
class oeGdprOptinOxcmp_user extends oeGdprOptinOxcmp_user_parent
{
    /**
     * Create new user.
     *
     * @return mixed
     */
    public function createUser()
    {
        if (false == $this->validateRegistrationOptin()) {
            //show error message on submit but not on page reload.
            if(oxRegistry::getConfig()->getRequestParameter('stoken')) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN', false, true);
                oxRegistry::get('oxUtilsView')->addErrorToDisplay('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN', false, true, 'oegdproptin_userregistration');
            }
        } else {
            return parent::createUser();
        }
    }

    /**
     * Mostly used for customer profile editing screen (OXID eShop ->
     * MY ACCOUNT). Checks if oUser is set (oxcmp_user::oUser) - if
     * not - executes oxcmp_user::_loadSessionUser(). If user unchecked newsletter
     * subscription option - removes him from this group. There is an
     * additional MUST FILL fields checking. Function returns true or false
     * according to user data submission status.
     *
     * Session variables:
     * <b>ordrem</b>
     *
     * @return  bool true on success, false otherwise
     */
    protected function _changeUser_noRedirect()
    {
        if (!$this->getSession()->checkSessionChallenge()) {
            return;
        }

        // no user ?
        $user = $this->getUser();
        if (!$user) {
            return;
        }

        $deliveryOptinValid = $this->validateDeliveryAddressOptIn();
        $invoiceOptinValid = $this->validateInvoiceAddressOptIn();

        if (false == $deliveryOptinValid) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS', false, true);
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS', false, true, 'oegdproptin_deliveryaddress');
        }
        if (false == $invoiceOptinValid) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS', false, true);
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS', false, true, 'oegdproptin_invoiceaddress');
        }

        $return = false;
        if ( (true == $deliveryOptinValid) && (true == $invoiceOptinValid)) {
            $return = parent::_changeUser_noRedirect();
        }
        
        return $return;
    }

    /**
     * Validate delivery address optin.
     * Needed if we get delivery address data from request.
     *
     * @return bool
     */
    protected function validateDeliveryAddressOptIn()
    {
        $return = true;
        $optin = (int) oxRegistry::getConfig()->getRequestParameter('oegdproptin_deliveryaddress');
        $changeExistigAddress = (int) oxRegistry::getConfig()->getRequestParameter('oegdproptin_changeDelAddress');
        $addressId = oxRegistry::getConfig()->getRequestParameter('oxaddressid');
        $deliveryAddressData = $this->_getDelAddressData();

        if (oxRegistry::getConfig()->getConfigParam('blOeGdprOptinDeliveryAddress')
            && ((null == $addressId) || ('-1' == $addressId) || (1 == $changeExistigAddress))
            && !empty($deliveryAddressData)
            && (1 !== $optin)
            ) {
            $return = false;
        }
        return $return;
    }

    /**
     * Validate user registration optin.
     *
     * @return bool
     */
    protected function validateRegistrationOptin()
    {
        $return = true;
        $optin = (int) oxRegistry::getConfig()->getRequestParameter('oegdproptin_userregistration');
        //1 is for guest buy, 3 for account creation
        $registrationOption = (int) oxRegistry::getConfig()->getRequestParameter('option');
        if (oxRegistry::getConfig()->getConfigParam('blOeGdprOptinUserRegistration') && (3 == $registrationOption) && (1 !== $optin)) {
            $return = false;
        }
        return $return;
    }

    /**
     * Validate invoice address optin.
     * Needed if user changes invoice address.
     *
     * @return bool
     */
    protected function validateInvoiceAddressOptIn()
    {
        $return = true;
        $optin = (int) oxRegistry::getConfig()->getRequestParameter('oegdproptin_invoiceaddress');
        $changeExistigAddress = (int) oxRegistry::getConfig()->getRequestParameter('oegdproptin_changeInvAddress');

        if (oxRegistry::getConfig()->getConfigParam('blOeGdprOptinInvoiceAddress')
            && (1 == $changeExistigAddress)
            && (1 !== $optin)
        ) {
            $return = false;
        }
        return $return;
    }
}

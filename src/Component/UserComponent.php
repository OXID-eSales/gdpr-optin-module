<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component;

use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Component\UserComponent
 */
class UserComponent extends UserComponent_parent
{
    use ServiceContainer;

    /**
     * Create new user.
     *
     * @return bool
     */
    public function createUser()
    {
        if (false == $this->validateRegistrationOptin()) {
            //show error message on submit but not on page reload.
            if ($this->getRequestParameter('stoken')) {
                Registry::get(UtilsView::class)->addErrorToDisplay(
                    'OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN',
                    false,
                    true
                );
                Registry::get(UtilsView::class)->addErrorToDisplay(
                    'OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN',
                    false,
                    true,
                    'oegdproptin_userregistration'
                );
            }
            return false;
        }

        return parent::createUser();
    }

    /**
     * Mostly used for customer profile editing screen (OXID eShop ->
     * MY ACCOUNT). Checks if oUser is set (\OxidEsales\Eshop\Application\Component\UserComponent::oUser) - if
     * not - executes \OxidEsales\Eshop\Application\Component\UserComponent::_loadSessionUser().
     * If user unchecked newsletter
     * subscription option - removes him from this group. There is an
     * additional MUST FILL fields checking. Function returns true or false
     * according to user data submission status.
     *
     * Session variables:
     * <b>ordrem</b>
     *
     * @return  bool|void true on success, false otherwise
     */
    protected function changeUserWithoutRedirect()
    {
        $session = Registry::getSession();
        if (!$session->checkSessionChallenge()) {
            return;
        }

        // no user ?
        $user = $this->getUser();
        if (!is_object($user)) {
            return;
        }

        $deliveryOptinValid = $this->validateDeliveryAddressOptIn();
        $invoiceOptinValid = $this->validateInvoiceAddressOptIn();

        if (false == $deliveryOptinValid) {
            $translation = Registry::getLang()->translateString('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS');
            $deliveryException = oxNew(\OxidEsales\Eshop\Core\Exception\InputException::class, $translation);
            Registry::getInputValidator()->addValidationError('oegdproptin_deliveryaddress', $deliveryException);
            Registry::get(UtilsView::class)->addErrorToDisplay(
                $deliveryException,
                false,
                true
            );
            Registry::get(UtilsView::class)->addErrorToDisplay(
                $deliveryException,
                false,
                true,
                'oegdproptin_deliveryaddress'
            );
        }
        if (false == $invoiceOptinValid) {
            $translation = Registry::getLang()->translateString('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS');
            $invoiceException = oxNew(\OxidEsales\Eshop\Core\Exception\InputException::class, $translation);
            Registry::getInputValidator()->addValidationError('oegdproptin_invoiceaddress', $invoiceException);
            Registry::get(UtilsView::class)->addErrorToDisplay(
                $invoiceException,
                false,
                true
            );
            Registry::get(UtilsView::class)->addErrorToDisplay(
                $invoiceException,
                false,
                true,
                'oegdproptin_invoiceaddress'
            );
        }

        $return = false;
        if ((true == $deliveryOptinValid) && (true == $invoiceOptinValid)) {
            $return = parent::changeUserWithoutRedirect();
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
        $optin = (int) $this->getRequestParameter('oegdproptin_deliveryaddress');
        $changeExistigAddress = (int) $this->getRequestParameter('oegdproptin_changeDelAddress');
        $addressId = $this->getRequestParameter('oxaddressid');
        $deliveryAddressData = $this->getDelAddressData();

        $moduleSettings = $this->getServiceFromContainer(ModuleSettings::class);

        if (
            $moduleSettings->showDeliveryOptIn()
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
        $optin = (int) $this->getRequestParameter('oegdproptin_userregistration');
        //1 is for guest buy, 3 for account creation
        $registrationOption = (int) $this->getRequestParameter('option');

        $moduleSettings = $this->getServiceFromContainer(ModuleSettings::class);

        if ($moduleSettings->showRegistrationOptIn() && (3 == $registrationOption) && (1 !== $optin)) {
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
        $optin = (int) $this->getRequestParameter('oegdproptin_invoiceaddress');
        $changeExistigAddress = (int) $this->getRequestParameter('oegdproptin_changeInvAddress');

        $moduleSettings = $this->getServiceFromContainer(ModuleSettings::class);

        if (
            $moduleSettings->showInvoiceOptIn()
            && (1 == $changeExistigAddress)
            && (1 !== $optin)
        ) {
            $return = false;
        }
        return $return;
    }

    /**
     * Wrapper for \OxidEsales\Eshop\Core\Request::getRequestParameter()
     *
     * @param string $name Parameter name
     *
     * @return mixed
     */
    protected function getRequestParameter($name)
    {
        return Registry::getRequest()
            ->getRequestParameter($name);
    }
}

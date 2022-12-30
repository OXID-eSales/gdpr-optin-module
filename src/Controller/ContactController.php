<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

/**
* @eshopExtension
* @mixin \OxidEsales\Eshop\Application\Controller\ContactController;
*/
class ContactController extends ContactController_parent
{
    use ServiceContainer;

    private const CONTACT_FORM_METHOD_DEFAULT = 'deletion';

    /**
     * Flag which shows if validation failed because of optin is not checked
     *
     * @var bool
     */
    protected $optInError = false;

    /**
     * Validation and contacts email sending
     *
     * @return bool
     */
    public function send()
    {
        $optInValue = Registry::getRequest()->getRequestParameter('c_oegdproptin');
        if ($this->isOptInValidationRequired() && !$optInValue) {
            Registry::get(UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE');
            $this->optInError = true;
            return false;
        }

        return parent::send();
    }

    /**
     * Check if validation failed because of the optin checkbox not checked
     *
     * @return bool
     */
    public function isOptInError()
    {
        return $this->optInError;
    }

    /**
     * Check if opt in validation is required.
     *
     * @return bool
     */
    public function isOptInValidationRequired()
    {
        return $this->getContactFormMethod() != self::CONTACT_FORM_METHOD_DEFAULT;
    }

    /**
     * Get currently selected contact form opt in method
     */
    private function getContactFormMethod(): string
    {
        $moduleSettings = $this->getServiceFromContainer(ModuleSettings::class);

        return $moduleSettings->getContactOptIn() ?
            $moduleSettings->getContactOptIn() : self::CONTACT_FORM_METHOD_DEFAULT;
    }
}

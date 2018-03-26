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

namespace OxidEsales\GdprOptinModule\Controller;

/**
 * Class ContactController
 * Extends \OxidEsales\Eshop\Application\Controller\ContactController
 *
 * @package OxidEsales\GdprOptinModule\Controller
 */
class ContactController extends ContactController_parent
{
    const CONTACT_FORM_METHOD_DEFAULT = 'deletion';

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
        $optInValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('c_oegdproptin');
        if ($this->isOptInValidationRequired() && !$optInValue) {
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE');
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
     *
     * @return string
     */
    private function getContactFormMethod()
    {
        $method = self::CONTACT_FORM_METHOD_DEFAULT;

        if ($configMethod = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('OeGdprOptinContactFormMethod')) {
            $method = $configMethod;
        }

        return $method;
    }
}

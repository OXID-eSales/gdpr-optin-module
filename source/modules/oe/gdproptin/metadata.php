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
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oegdproptin',
    'title'       => array(
        'de' => 'GDPR Opt-in',
        'en' => 'GDPR Opt-in',
    ),
    'description' => array(
        'de' => 'Das Modul stellt Opt-in-Funktionalit&auml;t f&uuml;r die Datenschutz-Grundverordnung (DSGVO) bereit',
        'en' => 'This module provides the opt-in functionality for the European General Data Protection Regulation (GDPR)',
    ),
    'thumbnail'   => 'out/pictures/logo.png',
    'version'     => '1.1.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => array(
        'oxwarticledetails' => 'oe/gdproptin/components/widgets/oegdproptinoxwarticledetails',
        'oxwreview' => 'oe/gdproptin/components/widgets/oegdproptinoxwreview',
        'oxcmp_user' => 'oe/gdproptin/components/oegdproptinoxcmp_user',
        'review' => 'oe/gdproptin/controllers/oegdproptinreview',
        'details' => 'oe/gdproptin/controllers/oegdproptindetails',
        'contact' => 'oe/gdproptin/controllers/oegdproptincontact'
    ),
    'files'       => array(
        'oegdproptinmodule' => 'oe/gdproptin/core/oegdproptinmodule.php'
    ),
    'templates'   => array(
        'oegdproptin_contact_form.tpl' => 'oe/gdproptin/views/form/contact.tpl'
    ),
    'blocks'      => array(
        array(
            'template' => 'form/user.tpl',
            'block'    => 'user_billing_address_form',
            'file'     => 'views/blocks/user_invoice_address_form.tpl',
        ),
        array(
            'template' => 'form/user.tpl',
            'block'    => 'user_shipping_address_form',
            'file'     => 'views/blocks/user_shipping_address_form.tpl',
        ),
        array(
            'template' => 'form/user.tpl',
            'block'    => 'user_form',
            'file'     => 'views/blocks/user_address.tpl',
        ),
        array(
            'template' => 'form/user.tpl',
            'block'    => 'user',
            'file'     => 'views/blocks/user.tpl',
        ),
        array(
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_change',
            'file'     => 'views/blocks/user_checkout_change.tpl',
        ),
        array(
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_shipping_feedback',
            'file'     => 'views/blocks/user_checkout_shipping_feedback.tpl',
        ),
        array(
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_billing_feedback',
            'file'     => 'views/blocks/user_checkout_billing_feedback.tpl',
        ),
        array(
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_select',
            'file'     => 'views/blocks/module_config.tpl',
        ),
        array(
            'template' => 'widget/reviews/reviews.tpl',
            'block'    => 'widget_reviews_form',
            'file'     => 'views/widget/reviews/reviews.tpl',
        ),
        array(
            'template' => 'form/fieldset/user_account.tpl',
            'block'    => 'user_account_newsletter',
            'file'     => 'views/blocks/user_account_newsletter.tpl',
        )
    ),
    'settings'    => array(
        array(
            'group' => 'oegdproptin_settings',
            'name'  => 'blOeGdprOptinInvoiceAddress',
            'type'  => 'bool',
            'value' => 'false'
        ),
        array(
            'group' => 'oegdproptin_settings',
            'name'  => 'blOeGdprOptinDeliveryAddress',
            'type'  => 'bool',
            'value' => 'false'
        ),
        array(
            'group' => 'oegdproptin_settings',
            'name'  => 'blOeGdprOptinUserRegistration',
            'type'  => 'bool',
            'value' => 'false'
        ),
        array(
            'group' => 'oegdproptin_settings',
            'name' => 'blOeGdprOptinProductReviews',
            'type' => 'bool',
            'value' => 'false'
        ),
        array(
            'group' => 'oegdproptin_contact_form',
            'name' => 'OeGdprOptinContactFormMethod',
            'type' => 'select',
            'value' => 'deletion',
            'constraints' => 'deletion|statistical',
        ),
    ),
    'events'      => array(
        'onActivate'   => 'oeGdprOptinModule::onActivate',
        'onDeactivate' => 'oeGdprOptinModule::onDeactivate',
    ),
);

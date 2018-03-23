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
$aModule = [
    'id'          => 'oegdproptin',
    'title'       => [
        'de' => 'GDPR Opt-in',
        'en' => 'GDPR Opt-in',
    ],
    'description' => [
        'de' => 'Das Modul stellt Opt-in-Funktionalit&auml;t f&uuml;r die Datenschutz-Grundverordnung (DSVGO) bereit',
        'en' => 'This module provides the opt-in functionality for the European General Data Protection Regulation (GDPR)',
    ],
    'thumbnail'   => 'logo.png',
    'version'     => '1.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => [
        'oxwarticledetails' => 'oe/gdproptin/Component/Widget/oegdproptinoxwarticledetails',
        'oxwreview' => 'oe/gdproptin/Component/Widget/oegdproptinoxwreview',
        'oxcmp_user' => 'oe/gdproptin/Component/oegdproptinoxcmp_user',
        'review' => 'oe/gdproptin/Controller/oegdproptinreview',
        'details' => 'oe/gdproptin/Controller/oegdproptindetails',
        'contact' => 'oe/gdproptin/Controller/oegdproptincontact'
    ],
    'files'       => [
        'oegdproptinmodule' => 'oe/gdproptin/Core/oegdproptinmodule.php'
    ],
    'templates'   => [
        'oegdproptin_contact_form.tpl' => 'oe/gdproptin/Application/views/form/contact.tpl'
    ],
    'blocks'      => [
        [
            'template' => 'form/user.tpl',
            'block'    => 'user_shipping_address_form',
            'file'     => 'Application/views/blocks/user_shipping_address_form.tpl',
        ],
        [
            'template' => 'form/user.tpl',
            'block'    => 'user_shipping_address',
            'file'     => 'Application/views/blocks/user_shipping_address.tpl',
        ],
        [
            'template' => 'form/user.tpl',
            'block'    => 'user',
            'file'     => 'Application/views/blocks/user.tpl',
        ],
        [
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_shipping_change',
            'file'     => 'Application/views/blocks/user_checkout_shipping_change.tpl',
        ],
        [
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_shipping_feedback',
            'file'     => 'Application/views/blocks/user_checkout_shipping_feedback.tpl',
        ],
        [
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_select',
            'file'     => 'Application/views/blocks/module_config.tpl',
        ],
        [
            'template' => 'widget/reviews/reviews.tpl',
            'block'    => 'widget_reviews_form',
            'file'     => 'Application/views/widget/reviews/reviews.tpl',
        ],
        [
            'template' => 'form/fieldset/user_account.tpl',
            'block'    => 'user_account_newsletter',
            'file'     => 'Application/views/blocks/user_account_newsletter.tpl',
        ]
    ],
    'settings'    => [
        [
            'group' => 'oegdproptin_settings',
            'name' => 'blOeGdprOptinDeliveryAddress',
            'type' => 'bool',
            'value' => 'false'
        ],
        [
            'group' => 'oegdproptin_settings',
            'name'  => 'blOeGdprOptinUserRegistration',
            'type'  => 'bool',
            'value' => 'false'
        ],
        [
            'group' => 'oegdproptin_settings',
            'name' => 'blOeGdprOptinProductReviews',
            'type' => 'bool',
            'value' => 'false'
        ],
        [
            'group' => 'oegdproptin_contact_form',
            'name' => 'OeGdprOptinContactFormMethod',
            'type' => 'select',
            'value' => 'deletion',
            'constraints' => 'deletion|statistical',
        ],
    ],
    'events'      => [
        'onActivate'   => 'oeGdprOptinModule::onActivate',
        'onDeactivate' => 'oeGdprOptinModule::onDeactivate',
    ],
];

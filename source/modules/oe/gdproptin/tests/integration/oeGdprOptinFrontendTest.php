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
 * Class oeGdprOptinFrontendTest
 */
class oeGdprOptinFrontendTest extends OxidTestCase
{
    const TEST_USER_ID = '_gdprtest';

    const TEST_ARTICLE_OXID = '_gdpr_test_product';

    /**
     * Test product.
     *
     * @var oxArticle
     */
    private $product =null;

    /**
     * Test set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->replaceBlocks();

        $this->createTestUser();
        $this->createTestProduct();
        $this->createBasket();
        oxRegistry::get('oxUtilsView')->getSmarty(true);
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->cleanUpTable('oxuser', 'oxid');
        $this->cleanUpTable('oxarticles', 'oxid');
        $this->cleanUpTable('oxtplblocks', 'oxid');

        oxRemClassModule('mod_oegdproptinoxwreview', 'oegdproptinoxwreview');

        parent::tearDown();
    }

    /**
     * @return array
     */
    public function providerDeliveryAddressOptin()
    {
        return array(
            'enable_optin_true_flow' => array(true, 'assertContains', 'flow'),
            'enable_optin_false_flow' => array(false, 'assertNotContains', 'flow'),
            'enable_optin_true_azure' => array(true, 'assertContains', 'azure'),
            'enable_optin_false_azure' => array(false, 'assertNotContains', 'azure'),
        );
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool   $blOeGdprOptinDeliveryAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testDeliveryAddressOptinForCheckout($blOeGdprOptinDeliveryAddress, $assertMethod, $theme)
    {
        oxRegistry::getSession()->setVariable('blshowshipaddress', true);
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $blOeGdprOptinDeliveryAddress);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput('user', 'form/user_checkout_change.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool $blOeGdprOptinDeliveryAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testDeliveryAddressOptinForUserAccount($blOeGdprOptinDeliveryAddress, $assertMethod, $theme)
    {
        oxRegistry::getSession()->setVariable('blshowshipaddress', true);
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $blOeGdprOptinDeliveryAddress);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput('account_user', 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }
    /**
     * @return array
     */
    public function providerInvoiceAddressOptin()
    {
        return array(
            'enable_optin_true_flow' => array(true, 'assertContains', 'flow'),
            'enable_optin_false_flow' => array(false, 'assertNotContains', 'flow'),

            'enable_optin_true_azure' => array(true, 'assertContains', 'azure'),
            'enable_optin_false_azure' => array(false, 'assertNotContains', 'azure')
        );
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerInvoiceAddressOptin
     *
     * @param bool   $reqireOptinInvoiceAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testInvoiceAddressOptinForCheckout($reqireOptinInvoiceAddress, $assertMethod, $theme)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $reqireOptinInvoiceAddress);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput('user', 'form/user_checkout_change.tpl');

        $this->$assertMethod('id="oegdproptin_invoiceaddress"', $content);
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerInvoiceAddressOptin
     *
     * @param bool   $reqireOptinInvoiceAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testInvoiceAddressOptinForUserAccount($reqireOptinInvoiceAddress, $assertMethod, $theme)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $reqireOptinInvoiceAddress);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput('account_user', 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_invoiceaddress"', $content);
    }

    /**
     * @return array
     */
    public function providerUserRegistrationOptin()
    {
        return array(
            'enable_optin_true_flow' => array(true, 'assertContains', 'flow'),
            'enable_optin_false_flow' => array(false, 'assertNotContains', 'flow'),
            'enable_optin_true_azure' => array(true, 'assertContains', 'azure'),
            'enable_optin_false_azure' => array(false, 'assertNotContains', 'azure')
        );
    }

    /**
     * Test checkbox visibility.
     * NOTE: user must not be logged in here. Need to simulate user registration.
     *
     * @dataProvider providerUserRegistrationOptin
     *
     * @param bool   $blOeGdprOptinUserRegistration
     * @param string $assertMethod
     * @param string $theme
     */
    public function testUserRegistrationOptin($blOeGdprOptinUserRegistration, $assertMethod, $theme)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $blOeGdprOptinUserRegistration);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);
        oxRegistry::getSession()->setUser(null);

        $addViewData = array();
        $addViewData['oxcmp_basket'] = oxNew('oxbasket');
        $addViewData['oConfig'] = oxRegistry::getConfig();
        $addViewData['sidebar'] = '';

        $content = $this->getTemplateOutput('register', 'page/account/register.tpl', $addViewData);
        $this->$assertMethod('id="oegdproptin_userregistration"', $content);
    }

    /**
     * @return array
     */
    public function providerUserCheckoutRegistrationOptin()
    {
        return array(
            'enable_optin_true_flow_noreg' => array(true, 'assertNotContains', 'flow', 1),
            'enable_optin_false_flow_noreg' => array(false, 'assertNotContains', 'flow', 1),
            'enable_optin_true_azure_noreg' => array(true, 'assertNotContains', 'azure', 1),
            'enable_optin_false_azure_noreg' => array(false, 'assertNotContains', 'azure', 1),
            'enable_optin_true_flow_reg' => array(true, 'assertContains', 'flow', 3),
            'enable_optin_false_flow_reg' => array(false, 'assertNotContains', 'flow', 3),
            'enable_optin_true_azure_reg' => array(true, 'assertContains', 'azure', 3),
            'enable_optin_false_azure_reg' => array(false, 'assertNotContains', 'azure', 3)
        );
    }

    /**
     * Test checkbox visibility during registration.
     * NOTE: user must not be logged in here. Need to simulate user registration.
     *
     * @dataProvider providerUserCheckoutRegistrationOptin
     *
     * @param bool   $blOeGdprOptinUserRegistration
     * @param string $assertMethod
     * @param string $theme
     * @param int    $option
     */
    public function testUserRegistrationOptinDuringCheckout($blOeGdprOptinUserRegistration, $assertMethod, $theme, $option)
    {
        $this->getConfig()->setRequestParameter('option', $option);
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $blOeGdprOptinUserRegistration);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);
        oxRegistry::getSession()->setUser(null);

        $addViewData = array();
        $addViewData['oxcmp_basket'] = oxNew('oxbasket');
        $addViewData['oConfig'] = oxRegistry::getConfig();
        $addViewData['sidebar'] = '';

        $content = $this->getTemplateOutput('user', 'page/checkout/user.tpl', $addViewData);

        $this->$assertMethod('id="oegdproptin_userregistration"', $content);
    }

    /**
     * Test contact form deletion optin
     */
    public function testContactFormDeletionOptIn()
    {
        oxRegistry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', 'deletion');
        oxRegistry::getConfig()->setConfigParam('sTheme', 'flow');
        $expected = oxRegistry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION");

        $content = $this->getTemplateOutput('Contact', 'oegdproptin_contact_form.tpl');

        $this->assertContains($expected, $content);
        $this->assertNotContains('name="c_oegdproptin"', $content);
    }

    /**
     * Test contact form statistical optin
     */
    public function testContactFormStatisticalOptIn()
    {
        oxRegistry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', 'statistical');
        oxRegistry::getConfig()->setConfigParam('sTheme', 'flow');
        $expected = oxRegistry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL");

        $content = $this->getTemplateOutput('Contact', 'oegdproptin_contact_form.tpl');

        $this->assertContains($expected, $content);
        $this->assertContains('name="c_oegdproptin"', $content);
    }

    /**
     * Creates filled basket object and stores it in session.
     */
    private function createBasket()
    {
        oxRegistry::getSession()->getBasket();
        $this->assertNull(oxRegistry::getSession()->getVariable('_newitem'));

        $basketComponent = oxNew('oxcmp_basket');
        $basketComponent->toBasket(self::TEST_ARTICLE_OXID, 1);
        $basket = $basketComponent->render();
        $this->assertEquals(1, $basket->getProductsCount());

        oxRegistry::getSession()->setBasket($basket);
    }

    /**
     * Create a test product.
     */
    private function createTestProduct()
    {
        $product = oxNew('oxArticle');
        $product->setId(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxshopid = new oxField(1);
        $product->oxarticles__oxtitle = new oxField(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxprice = new oxField(6.66);
        $product->save();

        $this->product = $product;
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
                'oxfname'     => 'Max',
                'oxlname'     => 'Mustermann',
                'oxusername'  => 'gdpruser@oxid.de',
                'oxpassword'  => md5('agent'),
                'oxactive'    => 1,
                'oxshopid'    => 1,
                'oxcountryid' => 'a7c40f631fc920687.20179984',
                'oxboni'      => '600',
                'oxstreet'    => 'Teststreet',
                'oxstreetnr'  => '101',
                'oxcity'      => 'Hamburg',
                'oxzip'       => '22769'
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
     * Test helper to replace header and footer as they are not needed for our tests.
     */
    private function replaceBlocks()
    {
        $shopId = oxRegistry::getConfig()->getShopId();
        $query = "INSERT INTO oxtplblocks (OXID, OXACTIVE, OXSHOPID, OXTEMPLATE, OXBLOCKNAME, OXPOS, OXFILE, OXMODULE) VALUES " .
                 "('_test_header', 1, '{$shopId}', 'layout/page.tpl', 'layout_header', 1, 'tests/integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_footer', 1, '{$shopId}', 'layout/footer.tpl', 'footer_main', 1, 'tests/integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_sidebar', 1, '{$shopId}', 'layout/sidebar.tpl', 'sidebar', 1, 'tests/integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_sgvo_icons', 1, '{$shopId}', 'layout/base.tpl', 'theme_svg_icons', 1, 'tests/integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_listitem', 1, '{$shopId}', 'page/review/review.tpl', 'widget_product_listitem_line_picturebox', 1, 'tests/integration/views/blocks/empty.tpl', 'oegdproptin')";

        oxDb::getDb()->execute($query);
    }

    /**
     * @return array
     */
    public function providerContactForm()
    {
        return array(
            'flow' => array('flow'),
            'azure' => array('azure'),
        );
    }

    /**
     * @return array
     */
    public function providerDetailsReviewOptin()
    {
        return array(
            'enable_optin_true_flow_art'   => array(true, 'assertContains', 'flow', 'oxwArticleDetails'),
            'enable_optin_false_flow_art'  => array(false, 'assertNotContains', 'flow', 'oxwArticleDetails'),
            'enable_optin_true_azure_art'  => array(true, 'assertContains', 'azure', 'oxwArticleDetails'),
            'enable_optin_false_azure_art' => array(false, 'assertNotContains', 'azure', 'oxwArticleDetails'),
            'enable_optin_true_azure_rev'  => array(true, 'assertContains', 'azure', 'review'),
            'enable_optin_false_azure_rev' => array(false, 'assertNotContains', 'azure', 'review'),
        );
    }

    /**
     * Test review form optin visibility.
     *
     * @dataProvider providerDetailsReviewOptin
     *
     * @param bool   $blOeGdprOptinProductReviews
     * @param string $assertMethod
     * @param string $theme
     * @param string $class
     */
    public function testDetailsReviewFormOptIn($blOeGdprOptinProductReviews, $assertMethod, $theme, $class)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinProductReviews', $blOeGdprOptinProductReviews);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput($class, 'widget/reviews/reviews.tpl', null, true);

        $this->$assertMethod('id="rvw_oegdproptin"', $content);

        //Error message always present in DOM, if checkbox present, but is hidden by default
        $message = oxRegistry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
        $this->$assertMethod($message, $content);
    }

    /**
     * @return array
     */
    public function providerOxwArticleDetailsReviewOptinError()
    {
        return array(
            'enable_optin_true_flow_art'   => array(true, 'assertContains', 'flow', 1),
            'enable_optin_false_flow_art'  => array(false, 'assertNotContains', 'flow', 0),
            'enable_optin_true_azure_art'  => array(true, 'assertContains', 'azure', 1),
            'enable_optin_false_azure_art' => array(false, 'assertNotContains', 'azure', 0)
        );
    }

    /**
     * Test review form optin error message visibility.
     *
     * @dataProvider providerOxwArticleDetailsReviewOptinError
     *
     * @param bool   $blOeGdprOptinProductReviews
     * @param string $assertMethod
     * @param string $theme
     * @param int    $count
     */
    public function testOxwArticleDetailsReviewFormOptInError($blOeGdprOptinProductReviews, $assertMethod, $theme, $count)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinProductReviews', $blOeGdprOptinProductReviews);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);

        $controller = $this->getMock('oegdproptinoxwarticledetails', array('isReviewOptInError'));
        $controller->expects($this->exactly($count))->method('isReviewOptInError')->will($this->returnValue(true));
        $controller->init();
        $controller->setViewProduct($this->product);

        $content = $this->doRender($controller, 'widget/reviews/reviews.tpl');

        $expected = oxRegistry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
        $this->$assertMethod($expected, $content);
    }

    /**
     * @return array
     */
    public function providerOxwReviewOptinError()
    {
        return array(
            'enable_optin_true_azure' => array(true, 'assertContains', 'azure', 1),
            'enable_optin_false_azure_rev' => array(false, 'assertNotContains', 'azure', 0),
        );
    }

    /**
     * Test review form optin error message visibility.
     *
     * @dataProvider providerOxwReviewOptinError
     *
     * @param bool   $blOeGdprOptinProductReviews
     * @param string $assertMethod
     * @param string $theme
     * @param int    $count
     */
    public function testOxwReviewFormOptInError($blOeGdprOptinProductReviews, $assertMethod, $theme, $count)
    {
        oxRegistry::getConfig()->setConfigParam('blOeGdprOptinProductReviews', $blOeGdprOptinProductReviews);
        oxRegistry::getConfig()->setConfigParam('sTheme', $theme);
        $this->getConfig()->setRequestParameter('rvw_oegdproptin', 'something');

        $controller = $this->getMock('oegdproptinreview', array('isReviewOptInError'));
        $controller->expects($this->exactly($count))->method('isReviewOptInError')->will($this->returnValue(true));
        $controller->init();
        $controller->setViewProduct($this->product);

        $content = $this->doRender($controller, 'page/review/review.tpl');

        $expected = oxRegistry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
        $this->$assertMethod($expected, $content);
    }

    /**
     * @param string $controllerName
     * @param string $template
     * @param null   $addViewData
     * @param bool   $setProduct Set true to add test product to view (needed for review tests)
     *
     * @return mixed
     */
    protected function getTemplateOutput($controllerName, $template, $addViewData = null, $setProduct = false)
    {
        $controller = oxNew($controllerName);
        $controller->init();

        if ($setProduct) {
            $controller->setViewProduct($this->product);
        }

        return $this->doRender($controller, $template, $addViewData);
    }

    /**
     * Test helper to render output.
     *
     * @param object $controller
     * @param string $template
     *
     * @return string
     */
    protected function doRender($controller, $template, $addViewData = null)
    {
        //prepare output
        $output = oxNew('oxOutput');
        $viewData = $output->processViewArray($controller->getViewData(), $controller->getClassName());
        if (is_array($addViewData)) {
            $viewData = array_merge($viewData, $addViewData);
        } else {
            $viewData['oxcmp_user'] = oxRegistry::getSession()->getUser();
            $viewData['oxcmp_basket'] = oxRegistry::getSession()->getBasket();
            $viewData['oConfig'] = oxRegistry::getConfig();
        }

        $controller->setViewData($viewData);
        return oxRegistry::get("oxUtilsView")->getTemplateOutput($template, $controller);
    }
}

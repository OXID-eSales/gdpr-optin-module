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
 * Class FrontendTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class FrontendTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    const TEST_USER_ID = '_gdprtest';

    const TEST_ARTICLE_OXID = '_gdpr_test_product';

    /**
     * Test product.
     *
     * @var \OxidEsales\Eshop\Application\Model\Article
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
        \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->getSmarty(true);
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->cleanUpTable('oxuser', 'oxid');
        $this->cleanUpTable('oxarticles', 'oxid');
        $this->cleanUpTable('oxtplblocks', 'oxid');

        parent::tearDown();
    }

    /**
     * @return array
     */
    public function providerDeliveryAddressOptin()
    {
        return [
            'enable_optin_true_flow' => [true, 'assertContains', 'flow'],
            'enable_optin_false_flow' => [false, 'assertNotContains', 'flow']
        ];
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool   $reqireOptinDeliveryAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testDeliveryAddressOptinForCheckout($reqireOptinDeliveryAddress, $assertMethod, $theme)
    {
        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('blshowshipaddress', true);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $reqireOptinDeliveryAddress);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\UserController::class, 'form/user_checkout_change.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }

    /**
     * Test checkbox visibility.
     *
     * @dataProvider providerDeliveryAddressOptin
     *
     * @param bool $reqireOptinDeliveryAddress
     * @param string $assertMethod
     * @param string $theme
     */
    public function testDeliveryAddressOptinForUserAccount($reqireOptinDeliveryAddress, $assertMethod, $theme)
    {
        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('blshowshipaddress', true);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinDeliveryAddress', $reqireOptinDeliveryAddress);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\AccountUserController::class, 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }

    /**
     * @return array
     */
    public function providerInvoiceAddressOptin()
    {
        return [
            'enable_optin_true_flow' => [true, 'assertContains', 'flow'],
            'enable_optin_false_flow' => [false, 'assertNotContains', 'flow']
        ];
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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $reqireOptinInvoiceAddress);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\UserController::class, 'form/user_checkout_change.tpl');

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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinInvoiceAddress', $reqireOptinInvoiceAddress);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\AccountUserController::class, 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_invoiceaddress"', $content);
    }

    /**
     * @return array
     */
    public function providerUserRegistrationOptin()
    {
        return [
            'enable_optin_true_flow' => [true, 'assertContains', 'flow'],
            'enable_optin_false_flow' => [false, 'assertNotContains', 'flow']
        ];
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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $blOeGdprOptinUserRegistration);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);
        \OxidEsales\Eshop\Core\Registry::getSession()->setUser(null);

        $addViewData = [];
        $addViewData['oxcmp_basket'] = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);
        $addViewData['oConfig'] = \OxidEsales\Eshop\Core\Registry::getConfig();
        $addViewData['sidebar'] = '';

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\RegisterController::class, 'page/account/register.tpl', $addViewData);
        $this->$assertMethod('id="oegdproptin_userregistration"', $content);
    }

    /**
     * @return array
     */
    public function providerUserCheckoutRegistrationOptin()
    {
        return [
            'enable_optin_true_flow_noreg' => [true, 'assertNotContains', 'flow', 1],
            'enable_optin_false_flow_noreg' => [false, 'assertNotContains', 'flow', 1],
            'enable_optin_true_flow_reg' => [true, 'assertContains', 'flow', 3],
            'enable_optin_false_flow_reg' => [false, 'assertNotContains', 'flow', 3]
        ];
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
        $this->setRequestParameter('option', $option);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinUserRegistration', $blOeGdprOptinUserRegistration);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);
        \OxidEsales\Eshop\Core\Registry::getSession()->setUser(null);

        $addViewData = [];
        $addViewData['oxcmp_basket'] = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);
        $addViewData['oConfig'] = \OxidEsales\Eshop\Core\Registry::getConfig();
        $addViewData['sidebar'] = '';

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\UserController::class, 'page/checkout/user.tpl', $addViewData);

        $this->$assertMethod('id="oegdproptin_userregistration"', $content);
    }

    /**
     * Test contact form deletion optin
     */
    public function testContactFormDeletionOptIn()
    {
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', 'deletion');
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', 'flow');
        $expected = \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION");

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\ContactController::class, 'form/contact.tpl');

        $this->assertContains($expected, $content);
        $this->assertNotContains('name="c_oegdproptin"', $content);
    }

    /**
     * Test contact form statistical optin
     */
    public function testContactFormStatisticalOptIn()
    {
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', 'statistical');
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', 'flow');
        $expected = \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL");

        $content = $this->getTemplateOutput(\OxidEsales\Eshop\Application\Controller\ContactController::class, 'form/contact.tpl');

        $this->assertContains($expected, $content);
        $this->assertContains('name="c_oegdproptin"', $content);
    }

    /**
     * Creates filled basket object and stores it in session.
     */
    private function createBasket()
    {
        \OxidEsales\Eshop\Core\Registry::getSession()->getBasket();
        $this->assertNull(\OxidEsales\Eshop\Core\Registry::getSession()->getVariable('_newitem'));

        $basketComponent = oxNew(\OxidEsales\Eshop\Application\Component\BasketComponent::class);
        $basketComponent->toBasket(self::TEST_ARTICLE_OXID, 1);
        $basket = $basketComponent->render();
        $this->assertEquals(1, $basket->getProductsCount());

        \OxidEsales\Eshop\Core\Registry::getSession()->setBasket($basket);
    }

    /**
     * Create a test product.
     */
    private function createTestProduct()
    {
        $product = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $product->setId(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $product->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(6.66);
        $product->save();

        $this->product = $product;
    }

    /**
     * Create a test user.
     */
    private function createTestUser()
    {
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->setId(self::TEST_USER_ID);
        $user->assign(
            [
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
            ]
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

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->load(self::TEST_USER_ID);
        \OxidEsales\Eshop\Core\Registry::getSession()->setUser($user);
        $user->setUser($user);
        $this->assertTrue($user->loadActiveUser());
    }

    /**
     * Test helper to replace header and footer as they are not needed for our tests.
     */
    private function replaceBlocks()
    {
        $shopId = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();
        $query = "INSERT INTO oxtplblocks (OXID, OXACTIVE, OXSHOPID, OXTEMPLATE, OXBLOCKNAME, OXPOS, OXFILE, OXMODULE) VALUES " .
                 "('_test_header', 1, '{$shopId}', 'layout/page.tpl', 'layout_header', 1, 'Tests/Integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_footer', 1, '{$shopId}', 'layout/footer.tpl', 'footer_main', 1, 'Tests/Integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_sidebar', 1, '{$shopId}', 'layout/sidebar.tpl', 'sidebar', 1, 'Tests/Integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_sgvo_icons', 1, '{$shopId}', 'layout/base.tpl', 'theme_svg_icons', 1, 'Tests/Integration/views/blocks/empty.tpl', 'oegdproptin'), " .
                 "('_test_listitem', 1, '{$shopId}', 'page/review/review.tpl', 'widget_product_listitem_line_picturebox', 1, 'Tests/Integration/views/blocks/empty.tpl', 'oegdproptin')";

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
    }

    /**
     * @return array
     */
    public function providerContactForm()
    {
        return [
            'flow' => ['flow']
        ];
    }

    /**
     * @return array
     */
    public function providerDetailsReviewOptin()
    {
        return [
            'enable_optin_true_flow_art'   => [true, 'assertContains', 'flow', 'oxwArticleDetails'],
            'enable_optin_false_flow_art'  => [false, 'assertNotContains', 'flow', 'oxwArticleDetails']
        ];
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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinProductReviews', $blOeGdprOptinProductReviews);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $content = $this->getTemplateOutput($class, 'widget/reviews/reviews.tpl', null, true);

        $this->$assertMethod('id="rvw_oegdproptin"', $content);

        //Error message always present in DOM, if checkbox present, but is hidden by default
        $message = \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
        $this->$assertMethod($message, $content);
    }

    /**
     * @return array
     */
    public function providerOxwArticleDetailsReviewOptinError()
    {
        return [
            'enable_optin_true_flow_art'   => [true, 'assertContains', 'flow', 1],
            'enable_optin_false_flow_art'  => [false, 'assertNotContains', 'flow', 0]
        ];
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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('blOeGdprOptinProductReviews', $blOeGdprOptinProductReviews);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('sTheme', $theme);

        $controller = $this->getMock(\OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class, ['isReviewOptInError']);
        $controller->expects($this->exactly($count))->method('isReviewOptInError')->will($this->returnValue(true));
        $controller->init();
        $controller->setViewProduct($this->product);

        $content = $this->doRender($controller, 'widget/reviews/reviews.tpl');

        $expected = \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
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
        $output = oxNew(\OxidEsales\Eshop\Core\Output::class);
        $viewData = $output->processViewArray($controller->getViewData(), $controller->getClassName());
        if (is_array($addViewData)) {
            $viewData = array_merge($viewData, $addViewData);
        } else {
            $viewData['oxcmp_user'] = \OxidEsales\Eshop\Core\Registry::getSession()->getUser();
            $viewData['oxcmp_basket'] = \OxidEsales\Eshop\Core\Registry::getSession()->getBasket();
            $viewData['oConfig'] = \OxidEsales\Eshop\Core\Registry::getConfig();
        }

        $controller->setViewData($viewData);
        return \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->getTemplateOutput($template, $controller);
    }
}

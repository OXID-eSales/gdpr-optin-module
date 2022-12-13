<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Component\BasketComponent;
use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\Eshop\Application\Controller\AccountUserController;
use OxidEsales\Eshop\Application\Controller\ContactController;
use OxidEsales\Eshop\Application\Controller\RegisterController;
use OxidEsales\Eshop\Application\Controller\UserController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Output;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;

final class FrontendTest extends IntegrationBaseTest
{
    use ServiceContainer;

    private const TEST_ARTICLE_OXID = '_gdpr_test_product';

    /**
     * Test product.
     *
     * @var Article
     */
    private $product = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestProduct();
        $this->createBasket();
    }

    public function providerDeliveryAddressOptin(): array
    {
        return [
            'enable_optin_true' => [true, 'assertStringContainsString'],
            'enable_optin_false' => [false, 'assertStringNotContainsString']
        ];
    }

    /**
     * @dataProvider providerDeliveryAddressOptin
     */
    public function testDeliveryAddressOptinForCheckout(bool $reqireOptinDeliveryAddress, string $assertMethod): void
    {
        Registry::getSession()->setVariable('blshowshipaddress', true);

        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::DELIVERY_OPT_IN,
            $reqireOptinDeliveryAddress,
            GdprOptinModule::MODULE_ID
        );

        $content = $this->getTemplateOutput(UserController::class, 'form/user_checkout_change.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }

    /**
     * @dataProvider providerDeliveryAddressOptin
     */
    public function testDeliveryAddressOptinForUserAccount(bool $reqireOptinDeliveryAddress, string $assertMethod): void
    {
        Registry::getSession()->setVariable('blshowshipaddress', true);

        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::DELIVERY_OPT_IN,
            $reqireOptinDeliveryAddress,
            GdprOptinModule::MODULE_ID
        );

        $content = $this->getTemplateOutput(AccountUserController::class, 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_deliveryaddress"', $content);
    }

    public function providerInvoiceAddressOptin(): array
    {
        return [
            'enable_optin_true' => [true, 'assertStringContainsString'],
            'enable_optin_false' => [false, 'assertStringNotContainsString']
        ];
    }

    /**
     * @dataProvider providerInvoiceAddressOptin
     */
    public function testInvoiceAddressOptinForCheckout(bool $reqireOptinInvoiceAddress, string $assertMethod): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            $reqireOptinInvoiceAddress,
            GdprOptinModule::MODULE_ID
        );

        $content = $this->getTemplateOutput(
            UserController::class,
            'form/user_checkout_change.tpl'
        );

        $this->$assertMethod('id="oegdproptin_invoiceaddress"', $content);
    }

    /**
     * @dataProvider providerInvoiceAddressOptin
     */
    public function testInvoiceAddressOptinForUserAccount(bool $reqireOptinInvoiceAddress, string $assertMethod): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            $reqireOptinInvoiceAddress,
            GdprOptinModule::MODULE_ID
        );

        $content = $this->getTemplateOutput(AccountUserController::class, 'form/user.tpl');

        $this->$assertMethod('id="oegdproptin_invoiceaddress"', $content);
    }

    public function providerUserRegistrationOptin(): array
    {
        return [
            'enable_optin_true' => [true, 'assertStringContainsString'],
            'enable_optin_false' => [false, 'assertStringNotContainsString']
        ];
    }

    public function providerUserCheckoutRegistrationOptin(): array
    {
        return [
            'enable_optin_true_noreg' => [true, 'assertStringNotContainsString', 1],
            'enable_optin_false_noreg' => [false, 'assertStringNotContainsString', 1],
            'enable_optin_true_reg' => [true, 'assertStringContainsString', 3],
            'enable_optin_false_reg' => [false, 'assertStringNotContainsString', 3]
        ];
    }

    public function testContactFormDeletionOptIn(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            'deletion',
            GdprOptinModule::MODULE_ID
        );

        $expected = Registry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION");

        $content = $this->getTemplateOutput(ContactController::class, 'form/contact.tpl');

        $this->assertStringContainsString($expected, $content);
        $this->assertStringNotContainsString('name="c_oegdproptin"', $content);
    }

    public function testContactFormStatisticalOptIn(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            'statistical',
            GdprOptinModule::MODULE_ID
        );

        $expected = Registry::getLang()->translateString("OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL");

        $content = $this->getTemplateOutput(ContactController::class, 'form/contact.tpl');

        $this->assertStringContainsString($expected, $content);
        $this->assertStringContainsString('name="c_oegdproptin"', $content);
    }

    public function providerDetailsReviewOptin(): array
    {
        return [
            'enable_optin_true_art'   => [true, 'assertStringContainsString', 'oxwArticleDetails'],
            'enable_optin_false_art'  => [false, 'assertStringNotContainsString', 'oxwArticleDetails']
        ];
    }

    /**
     * @dataProvider providerDetailsReviewOptin
     */
    public function testDetailsReviewFormOptIn(
        bool $blOeGdprOptinProductReviews,
        string $assertMethod,
        string $class
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $blOeGdprOptinProductReviews,
            GdprOptinModule::MODULE_ID
        );

        $content = $this->getTemplateOutput(
            $class,
            'widget/reviews/reviews.tpl',
            null,
            true
        );

        $this->$assertMethod('id="rvw_oegdproptin"', $content);

        //Error message always present in DOM, if checkbox present, but is hidden by default
        $message = Registry::getLang()->translateString("OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE");
        $this->$assertMethod($message, $content);
    }

    public function providerOxwArticleDetailsReviewOptinError(): array
    {
        return [
            'enable_optin_true_art' => [true, 'assertStringContainsString', 1],
            'enable_optin_false_art' => [false, 'assertStringNotContainsString', 0]
        ];
    }

    private function getTemplateOutput(
        string $controllerName,
        string $template,
        array $addViewData = null,
        bool $setProduct = false //Set true to add test product to view (needed for review tests)
    ): string {
        $controller = oxNew($controllerName);
        $controller->init();

        if ($setProduct) {
            $controller->setViewProduct($this->product);
        }

        return $this->doRender($controller, $template, $addViewData);
    }

    /**
     * Test helper to render output.
     */
    private function doRender(object $controller, string $template, array $addViewData = null): string
    {
        //prepare output
        $output = oxNew(Output::class);
        $viewData = $output->processViewArray($controller->getViewData(), $controller->getClassKey());
        if (is_array($addViewData)) {
            $viewData = array_merge($viewData, $addViewData);
        } else {
            $viewData['oxcmp_user'] = Registry::getSession()->getUser();
            $viewData['oxcmp_basket'] = Registry::getSession()->getBasket();
            $viewData['oConfig'] = Registry::getConfig();
        }

        $controller->setViewData($viewData);
        return Registry::get(UtilsView::class)->getTemplateOutput($template, $controller);
    }

    /**
     * Creates filled basket object and stores it in session.
     */
    private function createBasket(): void
    {
        Registry::getSession()->getBasket();
        $this->assertNull(Registry::getSession()->getVariable('_newitem'));

        $basketComponent = oxNew(BasketComponent::class);
        $basketComponent->toBasket(self::TEST_ARTICLE_OXID, 1);
        $basket = $basketComponent->render();
        $this->assertEquals(1, $basket->getProductsCount());

        Registry::getSession()->setBasket($basket);
    }

    private function createTestProduct(): void
    {
        $product = oxNew(Article::class);
        $product->setId(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxshopid = new Field(1);
        $product->oxarticles__oxtitle = new Field(self::TEST_ARTICLE_OXID);
        $product->oxarticles__oxprice = new Field(6.66);
        $product->save();

        $this->product = $product;
    }
}

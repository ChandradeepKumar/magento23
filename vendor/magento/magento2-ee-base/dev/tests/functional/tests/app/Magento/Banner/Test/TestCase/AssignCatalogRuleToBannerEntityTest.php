<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Customer\Test\Fixture\Customer;
use Magento\CatalogRule\Test\Fixture\CatalogRule;
use Magento\Banner\Test\Fixture\Banner;
use Magento\Banner\Test\Page\Adminhtml\BannerNew;
use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\CatalogRule\Test\Page\Adminhtml\CatalogRuleNew;
use Magento\CatalogRule\Test\Page\Adminhtml\CatalogRuleIndex;
use Magento\Mtf\Util\Command\Cli\Indexer;

/**
 * Preconditions:
 * 1. Create products.
 * 2. Create customer.
 * 3. Create Banner.
 * 4. Create Widget.
 *
 * Steps:
 * 1. Create and Apply new Catalog Price.
 * 2. Go to Content > Banners, open banner.
 * 3. Go to Related Promotions tab, select previously created catalog price rule and save.
 * 4. Go to frontend as not Logged In Customer. Observe home page.
 * 5. Perform all assertions.
 *
 * @group Catalog_Price_Rules
 * @ZephyrId MAGETWO-12389
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssignCatalogRuleToBannerEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Catalog rule form page.
     *
     * @var CatalogRuleNew
     */
    protected $catalogRuleNew;

    /**
     * Catalog rule grid page.
     *
     * @var CatalogRuleIndex
     */
    protected $catalogRuleIndex;

    /**
     * Factory for fixtures.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Banner grid page.
     *
     * @var BannerIndex
     */
    protected $bannerIndex;

    /**
     * Banner form page.
     *
     * @var BannerNew
     */
    protected $bannerNew;

    /**
     * Indexer.
     *
     * @var Indexer
     */
    private $indexer;

    /**
     * Injecting data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param CatalogRuleNew $catalogRuleNew
     * @param CatalogRuleIndex $catalogRuleIndex
     * @param BannerIndex $bannerIndex
     * @param BannerNew $bannerNew
     * @param Indexer $indexer
     * @return void
     */
    public function __inject(
        FixtureFactory $fixtureFactory,
        CatalogRuleNew $catalogRuleNew,
        CatalogRuleIndex $catalogRuleIndex,
        BannerIndex $bannerIndex,
        BannerNew $bannerNew,
        Indexer $indexer
    ) {
        $this->catalogRuleNew = $catalogRuleNew;
        $this->catalogRuleIndex = $catalogRuleIndex;
        $this->fixtureFactory = $fixtureFactory;
        $this->bannerIndex = $bannerIndex;
        $this->bannerNew = $bannerNew;
        $this->indexer = $indexer;
    }

    /**
     * Create Catalog Price Rule.
     *
     * @param string $catalogPriceRule
     * @param Category $category
     * @param Customer $customer
     * @param Banner $banner
     * @param string $products
     * @param string $widget
     * @return array
     * @throws \Exception
     */
    public function test(
        $catalogPriceRule,
        Category $category,
        Customer $customer,
        Banner $banner,
        $products,
        $widget
    ) {
        $this->markTestSkipped('MAGETWO-50165');
        $customer->persist();
        $category->persist();
        $banner->persist();
        $widget = $this->createWidget($widget, $banner);
        $categoryId = $category->getId();
        $replace = sprintf('[Category|is|%s]', $categoryId);
        $catalogPriceRule = $this->fixtureFactory->createByCode(
            'catalogRule',
            [
                'dataset' => $catalogPriceRule,
                'data' => [
                    'rule' => $replace
                ]
            ]
        );
        $catalogPriceRule->persist();
        $this->indexer->reindex(['catalogrule_product', 'catalog_product_price']);
        $this->bannerIndex->open();
        $this->bannerIndex->getGrid()->searchAndOpen(['banner' => $banner->getName()]);
        $this->bannerNew->getBannerForm()->openTab('related_promotions');
        /** @var \Magento\Banner\Test\Block\Adminhtml\Banner\Edit\Tab\RelatedPromotions $tab */
        $tab = $this->bannerNew->getBannerForm()->getTab('related_promotions');
        //choose catalog price rule
        $filter = ['name' => $catalogPriceRule->getName()];
        $tab->selectRelatedCatalogPriceRule($filter);
        $this->bannerNew->getPageMainActions()->save();

        $data = [];
        for ($i = 0; $i < count($products); $i++) {
            $data[] = ['category_ids' => ['category' => $category]];
        }
        $products = $this->objectManager->create(
            \Magento\Catalog\Test\TestStep\CreateProductsStep::class,
            ['data' => $data, 'products' => $products]
        )->run();

        return [
            'products' => $products['products'],
            'widget' => $widget,
            'customer' => $customer
        ];
    }

    /**
     * Create Widget.
     *
     * @param string $widget
     * @param Banner $banner
     * @return \Magento\Banner\Test\Fixture\BannerWidget
     */
    protected function createWidget($widget, Banner $banner)
    {
        $widget = $this->fixtureFactory->create(
            \Magento\Banner\Test\Fixture\BannerWidget::class,
            [
                'dataset' => $widget,
                'data' => [
                    'parameters' => [
                        'display_mode' => 'Specified Dynamic Blocks',
                        'entities' => [$banner]
                    ]
                ]
            ]
        );
        $widget->persist();

        return $widget;
    }

    /**
     * Deleted catalog price rules.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Widget\Test\TestStep\DeleteAllWidgetsStep::class)->run();
        $this->objectManager->create(\Magento\CatalogRule\Test\TestStep\DeleteAllCatalogRulesStep::class)->run();
    }
}

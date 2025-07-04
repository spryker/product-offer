<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferChecker;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface;
use Spryker\Zed\ProductOffer\Business\Counter\ProductOfferCartItemQuantityCounter;
use Spryker\Zed\ProductOffer\Business\Counter\ProductOfferCartItemQuantityCounterInterface;
use Spryker\Zed\ProductOffer\Business\Extractor\QuoteOriginalSalesOrderItemExtractor;
use Spryker\Zed\ProductOffer\Business\Extractor\QuoteOriginalSalesOrderItemExtractorInterface;
use Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGenerator;
use Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface;
use Spryker\Zed\ProductOffer\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\ProductOffer\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilter;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferStatusReader;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferStatusReaderInterface;
use Spryker\Zed\ProductOffer\Business\Trigger\ProductEventTrigger;
use Spryker\Zed\ProductOffer\Business\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\ProductOffer\Business\Validator\ProductOfferCheckoutValidator;
use Spryker\Zed\ProductOffer\Business\Validator\ProductOfferCheckoutValidatorInterface;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriter;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToEventInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Business\Trigger\ProductEventTriggerInterface
     */
    public function createProductEventTrigger(): ProductEventTriggerInterface
    {
        return new ProductEventTrigger($this->getEventFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface
     */
    public function createProductOfferWriter(): ProductOfferWriterInterface
    {
        return new ProductOfferWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createProductOfferReferenceGenerator(),
            $this->createProductEventTrigger(),
            $this->getConfig(),
            $this->getProductOfferPostCreatePlugins(),
            $this->getProductOfferPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface
     */
    public function createInactiveProductOfferItemsFilter(): InactiveProductOfferItemsFilterInterface
    {
        return new InactiveProductOfferItemsFilter(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface
     */
    public function createItemProductOfferChecker(): ItemProductOfferCheckerInterface
    {
        return new ItemProductOfferChecker($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Reader\ProductOfferStatusReaderInterface
     */
    public function createProductOfferStatusReader(): ProductOfferStatusReaderInterface
    {
        return new ProductOfferStatusReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToEventInterface
     */
    protected function getEventFacade(): ProductOfferToEventInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductOfferToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->getProductOfferExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface
     */
    public function createProductOfferReferenceGenerator(): ProductOfferReferenceGeneratorInterface
    {
        return new ProductOfferReferenceGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Extractor\QuoteOriginalSalesOrderItemExtractorInterface
     */
    public function createQuoteOriginalSalesOrderItemExtractor(): QuoteOriginalSalesOrderItemExtractorInterface
    {
        return new QuoteOriginalSalesOrderItemExtractor();
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface>
     */
    public function getProductOfferPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface>
     */
    public function getProductOfferPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface>
     */
    public function getProductOfferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Validator\ProductOfferCheckoutValidatorInterface
     */
    public function createProductOfferCheckoutValidator(): ProductOfferCheckoutValidatorInterface
    {
        return new ProductOfferCheckoutValidator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Counter\ProductOfferCartItemQuantityCounterInterface
     */
    public function createProductOfferCartItemQuantityCounter(): ProductOfferCartItemQuantityCounterInterface
    {
        return new ProductOfferCartItemQuantityCounter();
    }
}

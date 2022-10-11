<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 */
class ProductOfferFacade extends AbstractFacade implements ProductOfferFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function get(ProductOfferCriteriaTransfer $productOfferCriteria): ProductOfferCollectionTransfer
    {
        return $this->getFactory()->createProductOfferReader()->get($productOfferCriteria);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaTransfer $productOfferCriteria): ?ProductOfferTransfer
    {
        return $this->getFactory()->createProductOfferReader()->findOne($productOfferCriteria);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferWriter()
            ->create($productOfferTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer
    {
        return $this->getFactory()
            ->createProductOfferWriter()
            ->update($productOfferTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveProductOfferItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createInactiveProductOfferItemsFilter()
            ->filterInactiveProductOfferItems($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductOffer(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createItemProductOfferChecker()
            ->checkItemProductOffer($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array
    {
        return $this->getFactory()->createProductOfferStatusReader()->getApplicableApprovalStatuses($currentStatus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        return $this->getFactory()
            ->createProductOfferCheckoutValidator()
            ->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer {
        return $this->getFactory()
            ->createProductOfferCartItemQuantityCounter()
            ->countCartItemQuantity($itemsInCart, $itemTransfer);
    }
}

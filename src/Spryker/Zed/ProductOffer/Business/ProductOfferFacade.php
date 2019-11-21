<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStoreCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStoreCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
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
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer
    {
        return $this->getRepository()->find($productOfferCriteriaFilter);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer
    {
        return $this->getRepository()->findOne($productOfferCriteriaFilter);
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
        return $this->getEntityManager()->createProductOffer($productOfferTransfer);
    }
}

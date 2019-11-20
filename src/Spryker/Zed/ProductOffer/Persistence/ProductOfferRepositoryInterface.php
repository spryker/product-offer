<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStoreCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStoreCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer;

    /**
     * @param ProductOfferStoreCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return ProductOfferStoreCollectionTransfer
     */
    public function getProductOfferStores(ProductOfferStoreCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferStoreCollectionTransfer;
}

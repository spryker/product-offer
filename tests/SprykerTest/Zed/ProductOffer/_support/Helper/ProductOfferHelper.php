<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\Transfer\ProductOfferTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOfferHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function haveProductOffer(array $seedData = []): ProductOfferTransfer
    {
        $productOfferTransfer = (new ProductOfferBuilder($seedData))->build();
        $productOfferTransfer->setIdProductOffer(null);

        $productOfferTransfer = $this->getLocator()
            ->productOffer()
            ->facade()
            ->create($productOfferTransfer);

        return $productOfferTransfer;
    }
}

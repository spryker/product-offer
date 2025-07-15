<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductOffer;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductOffer\Expander\OriginalSalesOrderItemGroupKeyExpander;
use Spryker\Service\ProductOffer\Expander\OriginalSalesOrderItemGroupKeyExpanderInterface;

class ProductOfferServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductOffer\Expander\OriginalSalesOrderItemGroupKeyExpanderInterface
     */
    public function createOriginalSalesOrderItemGroupKeyExpander(): OriginalSalesOrderItemGroupKeyExpanderInterface
    {
        return new OriginalSalesOrderItemGroupKeyExpander();
    }
}

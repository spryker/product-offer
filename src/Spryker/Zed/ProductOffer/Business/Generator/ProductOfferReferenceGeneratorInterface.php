<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Generator;

interface ProductOfferReferenceGeneratorInterface
{
    /**
     * @param int $idProductOffer
     *
     * @return string
     */
    public function generateProductOfferReferenceById(int $idProductOffer): string;
}

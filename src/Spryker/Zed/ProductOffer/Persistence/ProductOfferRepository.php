<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferPersistenceFactory getFactory()
 */
class ProductOfferRepository extends AbstractRepository implements ProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();

        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteriaFilter);

        $productOfferEntities = $productOfferQuery->find();

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $this->getFactory()->createPropelProductOfferMapper()
                ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));

            foreach ($this->findStoresByFkProductOffer($productOfferTransfer->getIdProductOffer()) as $storeTransfer) {
                $productOfferTransfer->addStore($storeTransfer);
            }

            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer
    {
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();
        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteriaFilter);

        $productOfferEntity = $productOfferQuery->findOne();

        if (!$productOfferEntity) {
            return null;
        }

        return $this->getFactory()->createPropelProductOfferMapper()
            ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, new ProductOfferTransfer());
    }

    /**
     * @param int $fkProductOffer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function findStoresByFkProductOffer(int $fkProductOffer): array
    {
        $storeEntities = $this->getFactory()->getStorePropelQuery()
            ->useSpyProductOfferStoreQuery()
                ->filterByFkProductOffer($fkProductOffer)
            ->endUse()
            ->find()
            ->getData();

        $storeTransfers = [];
        foreach ($storeEntities as $storeEntity) {
            $storeTransfers[] = $this->getFactory()->createPropelProductOfferMapper()->mapStoreEntityToStoreTransfer(
                $storeEntity,
                new StoreTransfer()
            );
        }

        return $storeTransfers;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
    ): SpyProductOfferQuery {
        if ($productOfferCriteriaFilter->getConcreteSku()) {
            $productOfferQuery->filterByConcreteSku($productOfferCriteriaFilter->getConcreteSku());
        }

        if ($productOfferCriteriaFilter->getProductOfferReference()) {
            $productOfferQuery->filterByProductOfferReference($productOfferCriteriaFilter->getProductOfferReference());
        }

        if ($productOfferCriteriaFilter->getConcreteSkus()) {
            $productOfferQuery->filterByConcreteSku_In($productOfferCriteriaFilter->getConcreteSkus());
        }

        if ($productOfferCriteriaFilter->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($productOfferCriteriaFilter->getProductOfferReferences());
        }

        if ($productOfferCriteriaFilter->getPagination()) {
            $productOfferQuery = $this->getPaginatedCollection($productOfferQuery, $productOfferCriteriaFilter->getPagination());
        }

        if ($productOfferCriteriaFilter->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($productOfferCriteriaFilter->getIsActive());
        }

        if ($productOfferCriteriaFilter->getApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productOfferCriteriaFilter->getApprovalStatuses());
        }

        if ($productOfferCriteriaFilter->getIsActiveConcreteProduct() !== null) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN
            )
            ->where(SpyProductTableMap::COL_IS_ACTIVE, true);
        }

        return $productOfferQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }
}

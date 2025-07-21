<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Repository;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterfaceFactory as SearchResultsFactory;
use SwiftOtter\GiftCard\Model\GiftCard;
use SwiftOtter\GiftCard\Model\GiftCardFactory as GiftCardFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory as GiftCardCollectionFactory;

class GiftCardRepository
{
    /** @var GiftCardResourceModel  */
    protected GiftCardResourceModel $resource;

    /** @var GiftCardFactory  */
    protected GiftCardFactory $modelFactory;

    /** @var GiftCardCollectionFactory  */
    protected GiftCardCollectionFactory $collectionFactory;

    /** @var SearchResultsFactory  */
    protected SearchResultsFactory $searchResultsFactory;

    /** @var CollectionProcessorInterface  */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * Model data storage
     *
     * @var array
     */
    private array $giftCards = [];
    public function __construct(
        GiftCardResourceModel $resource,
        GiftCardFactory $giftCardFactory,
        GiftCardCollectionFactory $giftCardCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
    ) {
        $this->resource = $resource;
        $this->modelFactory = $giftCardFactory;
        $this->collectionFactory = $giftCardCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save GiftCard data
     *
     * @param GiftCardInterface $giftCard
     * @return GiftCardInterface
     * @throws CouldNotSaveException|NoSuchEntityException
     */
    public function save(GiftCardInterface $giftCard)
    {
        try {
            if ($giftCard->getId()) {
                $giftCard = $this->getById($giftCard->getId())->addData($giftCard->getData());
            }
            $this->resource->save($giftCard);
            unset($this->giftCards[$giftCard->getId()]);
        } catch (\Exception $e) {
            if ($giftCard->getId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save GiftCard with ID %1. Error: %2',
                        [$giftCard->getId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new GiftCard. Error: %1', $e->getMessage()));
        }

        return $giftCard;
    }

    /**
     * Load Block data by given Block Identity
     *
     * @param int $giftCardId
     * @return GiftCardInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $giftCardId): GiftCardInterface
    {
        if (!isset($this->giftCards[$giftCardId])) {

            $giftCard = $this->modelFactory->create();

            $this->resource->load($giftCard, $giftCardId);

            if (!$giftCard->getId()) {
                throw new NoSuchEntityException(__('GiftCard with specified ID "%1" not found.', $giftCardId));
            }
            $this->giftCards[$giftCardId] = $giftCard;
        }

        return $this->giftCards[$giftCardId];
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getByCode(string $giftCardCode):GiftCardInterface
    {
        $giftCard = $this->modelFactory->create();
        $this->resource->load($giftCard, $giftCardCode, 'code');
        if (!$giftCard->getId()) {
            throw new NoSuchEntityException(__('GiftCard with specified Code "%1" not found.', $giftCardCode));
        }
        return $giftCard;
    }


    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var \SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var GiftCardSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }


    /**
     * @throws CouldNotDeleteException
     */
    public function delete(GiftCardInterface $giftCard): bool
    {
        try {
            $this->resource->delete($giftCard);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Block by given Block Identity
     *
     * @param int $giftCardId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $giftCardId): bool
    {
        return $this->delete($this->getById($giftCardId));
    }

}

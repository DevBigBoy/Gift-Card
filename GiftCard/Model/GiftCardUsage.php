<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResource;

/**
 * Gift Card Usage Model
 */
class GiftCardUsage extends AbstractModel implements GiftCardUsageInterface
{
    /**
     * Cache tag
     */
    const string CACHE_TAG = 'swift_otter_gift_card_usage';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(GiftCardUsageResource::class);
    }

    /**
     * Get gift card ID
     *
     * @return int
     */
    public function getGiftCardId(): int
    {
        return $this->getData(self::GIFT_CARD_ID);
    }

    /**
     * Set gift card ID
     *
     * @param int $giftCardId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setGiftCardId(int $giftCardId): GiftCardUsageInterface
    {
        $this->setData(self::GIFT_CARD_ID, $giftCardId);
        return $this;
    }

    /**
     * Get order ID
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return (int) $this->getData(self::ORDER_ID);
    }

    /**
     * Set order ID
     *
     * @param int $orderId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setOrderId(int $orderId): GiftCardUsageInterface
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }

    /**
     * Get value change
     *
     * @return float
     */
    public function getValueChange(): float
    {
        return $this->getData(self::VALUE_CHANGE);
    }

    /**
     * Set value change
     *
     * @param float $valueChange
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setValueChange(float $valueChange): GiftCardUsageInterface
    {
        $this->setData(self::VALUE_CHANGE, $valueChange);
        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes(): string
    {
        return $this->getData(self::NOTES);
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setNotes(string $notes): GiftCardUsageInterface
    {
        $this->setData(self::NOTES, $notes);
        return $this;
    }

    /**
     * Get created at
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     * @return GiftCardUsageInterface
     */
    public function setCreatedAt(\DateTime $createdAt): GiftCardUsageInterface
    {
        $this->setData(self::CREATED_AT, $createdAt->format('Y-m-d H:i:s'));
        return $this;
    }

    /**
     * Check if this is a debit transaction (negative value)
     *
     * @return bool
     */
    public function isDebit(): bool
    {
        return $this->getValueChange() < 0;
    }

    /**
     * Check if this is a credit transaction (positive value)
     *
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->getValueChange() > 0;
    }

    /**
     * Get the absolute value of the change
     *
     * @return float
     */
    public function getAbsoluteValue(): float
    {
        return abs($this->getValueChange() ?: 0);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Validate usage before save
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    //    public function beforeSave()
    //    {
    //        // Validate required fields
    //        if (!$this->getGiftCardId()) {
    //            throw new \Magento\Framework\Exception\LocalizedException(__('Gift card ID is required'));
    //        }
    //
    //        if (!$this->getOrderId()) {
    //            throw new \Magento\Framework\Exception\LocalizedException(__('Order ID is required'));
    //        }
    //
    //        if (!$this->getNotes()) {
    //            throw new \Magento\Framework\Exception\LocalizedException(__('Notes are required'));
    //        }
    //
    //        if ($this->getValueChange() === null || $this->getValueChange() === '') {
    //            throw new \Magento\Framework\Exception\LocalizedException(__('Value change is required'));
    //        }
    //
    //        return parent::beforeSave();
    //    }
}

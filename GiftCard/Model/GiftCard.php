<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResource;

/**
 * Gift Card Model
 */
class GiftCard extends AbstractModel implements GiftCardInterface
{
    /**
     * Cache tag
     */
    const string CACHE_TAG = 'swift_otter_gift_card';

    protected function _construct()
    {
        $this->_init(GiftCardResource::class);
    }

    /**
     * Get assigned customer ID
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int) $this->getData(self::ASSIGNED_CUSTOMER_ID);
    }

    /**
     * Set assigned customer ID
     *
     * @param int $customerId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCustomerId(int $customerId): GiftCardInterface
    {
        $this->setData(self::ASSIGNED_CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * Get gift card code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set gift card code
     *
     * @param string $code
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCode(string $code): GiftCardInterface
    {
        $this->setData(self::CODE, $code);
        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     *
     * @param int $status
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setStatus(int $status): GiftCardInterface
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * Get initial value
     *
     * @return float
     */
    public function getInitialValue(): float
    {
        return $this->getData(self::INITIAL_VALUE);
    }

    /**
     * Set initial value
     *
     * @param float $value
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setInitialValue(float $value): GiftCardInterface
    {
        $this->setData(self::INITIAL_VALUE, $value);
        return $this;
    }

    /**
     * Get current value
     *
     * @return float
     */
    public function getCurrentValue(): float
    {
        return $this->getData(self::CURRENT_VALUE);
    }

    /**
     * Set current value
     *
     * @param float $value
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCurrentValue(float $value): GiftCardInterface
    {
        $this->setData(self::CURRENT_VALUE, $value);
        return $this;
    }

    /**
     * Get created at
     *
     * @return \DateTime
     * @throws \DateMalformedStringException
     */
    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->getData(self::CREATED_AT));
    }

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCreatedAt(\DateTime $createdAt): GiftCardInterface
    {
        $this->setData(self::CREATED_AT, $createdAt->format('Y-m-d H:i:s'));
        return $this;
    }

    /**
     * Get updated at
     *
     * @return \DateTime
     * @throws \DateMalformedStringException
     */
    public function getUpdatedAt(): \DateTime
    {
        return new \DateTime($this->getData(self::UPDATED_AT));
    }

    /**
     * Set updated at
     *
     * @param \DateTime $updatedAt
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setUpdatedAt(\DateTime $updatedAt): GiftCardInterface
    {
        $this->setData(self::UPDATED_AT, $updatedAt->format('Y-m-d H:i:s'));
        return $this;
    }

    /**
     * Get recipient email
     *
     * @return string
     */
    public function getRecipientEmail(): string
    {
        return $this->getData(self::RECIPIENT_EMAIL);
    }

    /**
     * Set recipient email
     *
     * @param string $email
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setRecipientEmail(string $email): GiftCardInterface
    {
        $this->setData(self::RECIPIENT_EMAIL, $email);
        return $this;
    }

    /**
     * Get recipient name
     *
     * @return string
     */
    public function getRecipientName(): string
    {
        return $this->getData(self::RECIPIENT_NAME);
    }

    /**
     * Set recipient name
     *
     * @param string $name
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setRecipientName(string $name): GiftCardInterface
    {
        $this->setData(self::RECIPIENT_NAME, $name);
        return $this;
    }

    /**
     * Check if the gift card is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getStatus() == self::STATUS_ACTIVE;
    }

    /**
     * Check if gift card has available balance
     *
     * @return bool
     */
    public function hasBalance(): bool
    {
        return $this->getCurrentValue() > 0;
    }

    /**
     * Get available balance (alias for getCurrentValue)
     *
     * @return float|int
     */
    public function getBalance(): float|int
    {
        return $this->getCurrentValue() ?: 0;
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
}

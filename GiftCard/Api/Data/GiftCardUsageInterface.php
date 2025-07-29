<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Api\Data;

/**
 * Gift Card Usage Data Interface
 */
interface GiftCardUsageInterface
{
    /**
     * Constants for keys of a data array
     */
    const string ID = 'id';
    const string GIFT_CARD_ID = 'gift_card_id';
    const string ORDER_ID = 'order_id';
    const string VALUE_CHANGE = 'value_change';
    const string NOTES = 'notes';
    const string CREATED_AT = 'created_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get gift card ID
     *
     * @return int
     */
    public function getGiftCardId(): int;

    /**
     * Set gift card ID
     *
     * @param int $giftCardId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setGiftCardId(int $giftCardId): GiftCardUsageInterface;

    /**
     * Get order ID
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set order ID
     *
     * @param int $orderId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setOrderId(int $orderId): GiftCardUsageInterface;

    /**
     * Get value change
     *
     * @return float
     */
    public function getValueChange(): float;

    /**
     * Set value change
     *
     * @param float $valueChange
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setValueChange(float $valueChange): GiftCardUsageInterface;

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes(): string;

    /**
     * Set notes
     *
     * @param string $notes
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface
     */
    public function setNotes(string $notes): GiftCardUsageInterface;
    /**
     * Get created at
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     * @return GiftCardUsageInterface
     */
    public function setCreatedAt(\DateTime $createdAt): GiftCardUsageInterface;

}

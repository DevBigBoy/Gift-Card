<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Api\Data;

/**
 * Gift Card Data Interface
 */
interface GiftCardInterface
{
    /**
     * Constants for keys of a data array
     */
    const string ID = 'id';
    const string ASSIGNED_CUSTOMER_ID = 'assigned_customer_id';
    const string CODE = 'code';
    const string STATUS = 'status';
    const string INITIAL_VALUE = 'initial_value';
    const string CURRENT_VALUE = 'current_value';
    const string CREATED_AT = 'created_at';
    const string UPDATED_AT = 'updated_at';
    const string RECIPIENT_EMAIL = 'recipient_email';
    const string RECIPIENT_NAME = 'recipient_name';

    /**
     * Status constants
     */
    const int STATUS_ACTIVE = 1;
    const int STATUS_USED = 2;
    const int STATUS_EXPIRED = 3;
    const int STATUS_CANCELLED = 4;

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setId($id);

    /**
     * Get assigned customer ID
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Set assigned customer ID
     *
     * @param int $customerId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCustomerId(int $customerId): GiftCardInterface;

    /**
     * Get gift card code
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Set gift card code
     *
     * @param string $code
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCode(string $code): GiftCardInterface;

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set status
     *
     * @param int $status
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setStatus(int $status): GiftCardInterface;

    /**
     * Get initial value
     *
     * @return float
     */
    public function getInitialValue(): float;

    /**
     * Set initial value
     *
     * @param float $value
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setInitialValue(float $value): GiftCardInterface;

    /**
     * Get current value
     *
     * @return float
     */
    public function getCurrentValue(): float;

    /**
     * Set current value
     *
     * @param float $value
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCurrentValue(float $value): GiftCardInterface;

    /**
     * Get created at
     *
     * @return \DateTime
     * @throws \DateMalformedStringException
     */
    public function getCreatedAt(): \DateTime;

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setCreatedAt(\DateTime $createdAt): GiftCardInterface;

    /**
     * Get updated at
     *
     * @return \DateTime
     * @throws \DateMalformedStringException
     */
    public function getUpdatedAt(): \DateTime;

    /**
     * Set updated at
     *
     * @param \DateTime $updatedAt
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setUpdatedAt(\DateTime $updatedAt): GiftCardInterface;

    /**
     * Get recipient email
     *
     * @return string
     */
    public function getRecipientEmail(): string;

    /**
     * Set recipient email
     *
     * @param string $email
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setRecipientEmail(string $email): GiftCardInterface;

    /**
     * Get recipient name
     *
     * @return string
     */
    public function getRecipientName(): string;

    /**
     * Set recipient name
     *
     * @param string $name
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function setRecipientName(string $name): GiftCardInterface;

}

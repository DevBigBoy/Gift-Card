<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

/**
 * Gift Card Collection
 */
class Collection extends AbstractCollection
{
    /**
     * ID field name
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'swift_otter_gift_card_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'gift_card_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \SwiftOtter\GiftCard\Model\GiftCard::class,
            \SwiftOtter\GiftCard\Model\ResourceModel\GiftCard::class
        );
    }

    /**
     * Add filter by customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('assigned_customer_id', $customerId);
        return $this;
    }

    /**
     * Add filter for active gift cards
     *
     * @return $this
     */
    public function addActiveFilter()
    {
        $this->addFieldToFilter('status', GiftCardInterface::STATUS_ACTIVE);
        return $this;
    }

    /**
     * Add filter for gift cards with balance
     *
     * @return $this
     */
    public function addBalanceFilter()
    {
        $this->addFieldToFilter('current_value', ['gt' => 0]);
        return $this;
    }

    /**
     * Add filter for used gift cards
     *
     * @return $this
     */
    public function addUsedFilter()
    {
        $this->addFieldToFilter('status', GiftCardInterface::STATUS_USED);
        return $this;
    }

    /**
     * Add filter for expired gift cards
     *
     * @return $this
     */
    public function addExpiredFilter()
    {
        $this->addFieldToFilter('status', GiftCardInterface::STATUS_EXPIRED);
        return $this;
    }

    /**
     * Add filter by gift card code
     *
     * @param string $code
     * @return $this
     */
    public function addCodeFilter($code)
    {
        $this->addFieldToFilter('code', $code);
        return $this;
    }

    /**
     * Add filter by recipient email
     *
     * @param string $email
     * @return $this
     */
    public function addRecipientEmailFilter($email)
    {
        $this->addFieldToFilter('recipient_email', $email);
        return $this;
    }

    /**
     * Add filter by date range
     *
     * @param string $fromDate
     * @param string $toDate
     * @return $this
     */
    public function addDateRangeFilter($fromDate, $toDate)
    {
        $this->addFieldToFilter('created_at', ['from' => $fromDate, 'to' => $toDate]);
        return $this;
    }

    /**
     * Add filter by value range
     *
     * @param float $minValue
     * @param float $maxValue
     * @return $this
     */
    public function addValueRangeFilter($minValue, $maxValue)
    {
        if ($minValue !== null) {
            $this->addFieldToFilter('current_value', ['gteq' => $minValue]);
        }
        if ($maxValue !== null) {
            $this->addFieldToFilter('current_value', ['lteq' => $maxValue]);
        }
        return $this;
    }

    /**
     * Add status filter
     *
     * @param int|array $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * Join with customer table to get customer information
     *
     * @param array $fields
     * @return $this
     */
    public function joinCustomerData($fields = ['firstname', 'lastname', 'email'])
    {
        $this->getSelect()->joinLeft(
            ['customer' => $this->getTable('customer_entity')],
            'main_table.assigned_customer_id = customer.entity_id',
            $fields
        );
        return $this;
    }

    /**
     * Add total value to select
     *
     * @return $this
     */
    public function addTotalValueToSelect()
    {
        $this->getSelect()->columns([
            'total_initial_value' => 'SUM(initial_value)',
            'total_current_value' => 'SUM(current_value)'
        ]);
        return $this;
    }

    /**
     * Group by customer
     *
     * @return $this
     */
    public function groupByCustomer()
    {
        $this->getSelect()->group('assigned_customer_id');
        return $this;
    }

    /**
     * Group by status
     *
     * @return $this
     */
    public function groupByStatus()
    {
        $this->getSelect()->group('status');
        return $this;
    }

    /**
     * Order by creation date
     *
     * @param string $dir
     * @return $this
     */
    public function orderByCreatedAt($dir = 'DESC')
    {
        $this->setOrder('created_at', $dir);
        return $this;
    }

    /**
     * Order by current value
     *
     * @param string $dir
     * @return $this
     */
    public function orderByValue($dir = 'DESC')
    {
        $this->setOrder('current_value', $dir);
        return $this;
    }

    /**
     * Get gift cards that are expiring soon
     *
     * @param int $days Number of days from now
     * @return $this
     */
    public function addExpiringSoonFilter($days = 30)
    {
        $expirationDate = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        $this->addActiveFilter()
            ->addFieldToFilter('created_at', ['lt' => $expirationDate]);
        return $this;
    }

    /**
     * Get gift cards by recipient
     *
     * @param string $recipientEmail
     * @return $this
     */
    public function getByRecipient($recipientEmail)
    {
        $this->addRecipientEmailFilter($recipientEmail);
        return $this;
    }

    /**
     * Get statistics summary
     *
     * @return array
     */
    public function getStatistics()
    {
        $clone = $this->_cloneCollection();
        $clone->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $clone->getSelect()->columns([
            'total_count' => 'COUNT(*)',
            'active_count' => 'SUM(CASE WHEN status = ' . GiftCardInterface::STATUS_ACTIVE . ' THEN 1 ELSE 0 END)',
            'used_count' => 'SUM(CASE WHEN status = ' . GiftCardInterface::STATUS_USED . ' THEN 1 ELSE 0 END)',
            'expired_count' => 'SUM(CASE WHEN status = ' . GiftCardInterface::STATUS_EXPIRED . ' THEN 1 ELSE 0 END)',
            'total_initial_value' => 'SUM(initial_value)',
            'total_current_value' => 'SUM(current_value)',
            'average_initial_value' => 'AVG(initial_value)',
            'average_current_value' => 'AVG(current_value)'
        ]);

        return $clone->getConnection()->fetchRow($clone->getSelect());
    }

    /**
     * Clone collection for statistics
     *
     * @return $this
     */
    protected function _cloneCollection()
    {
        $collection = clone $this;
        $collection->clear();
        return $collection;
    }
}

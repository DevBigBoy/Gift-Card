<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Gift Card Usage Collection
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
    protected $_eventPrefix = 'swift_otter_gift_card_usage_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'gift_card_usage_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \SwiftOtter\GiftCard\Model\GiftCardUsage::class,
            \SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage::class
        );
    }

    /**
     * Add filter by gift card ID
     *
     * @param int $giftCardId
     * @return $this
     */
    public function addGiftCardFilter($giftCardId)
    {
        $this->addFieldToFilter('gift_card_id', $giftCardId);
        return $this;
    }

    /**
     * Add filter by order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function addOrderFilter($orderId)
    {
        $this->addFieldToFilter('order_id', $orderId);
        return $this;
    }

    /**
     * Add filter for debit transactions (negative values)
     *
     * @return $this
     */
    public function addDebitFilter()
    {
        $this->addFieldToFilter('value_change', ['lt' => 0]);
        return $this;
    }

    /**
     * Add filter for credit transactions (positive values)
     *
     * @return $this
     */
    public function addCreditFilter()
    {
        $this->addFieldToFilter('value_change', ['gt' => 0]);
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
            $this->addFieldToFilter('ABS(value_change)', ['gteq' => $minValue]);
        }
        if ($maxValue !== null) {
            $this->addFieldToFilter('ABS(value_change)', ['lteq' => $maxValue]);
        }
        return $this;
    }

    /**
     * Join with gift card table to get gift card information
     *
     * @param array $fields
     * @return $this
     */
    public function joinGiftCardData($fields = ['code', 'recipient_email', 'recipient_name'])
    {
        $this->getSelect()->joinLeft(
            ['gift_card' => $this->getTable('gift_card')],
            'main_table.gift_card_id = gift_card.id',
            $fields
        );
        return $this;
    }

    /**
     * Join with sales order table to get order information
     *
     * @param array $fields
     * @return $this
     */
    public function joinOrderData($fields = ['increment_id', 'customer_email', 'grand_total'])
    {
        $this->getSelect()->joinLeft(
            ['sales_order' => $this->getTable('sales_order')],
            'main_table.order_id = sales_order.entity_id',
            $fields
        );
        return $this;
    }

    /**
     * Join with customer table to get customer information
     *
     * @param array $fields
     * @return $this
     */
    public function joinCustomerData($fields = ['firstname', 'lastname'])
    {
        $this->joinGiftCardData(['assigned_customer_id']);
        $this->getSelect()->joinLeft(
            ['customer' => $this->getTable('customer_entity')],
            'gift_card.assigned_customer_id = customer.entity_id',
            $fields
        );
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
     * Order by value change
     *
     * @param string $dir
     * @return $this
     */
    public function orderByValue($dir = 'DESC')
    {
        $this->setOrder('ABS(value_change)', $dir);
        return $this;
    }

    /**
     * Group by gift card ID
     *
     * @return $this
     */
    public function groupByGiftCard()
    {
        $this->getSelect()->group('gift_card_id');
        return $this;
    }

    /**
     * Group by order ID
     *
     * @return $this
     */
    public function groupByOrder()
    {
        $this->getSelect()->group('order_id');
        return $this;
    }

    /**
     * Group by date
     *
     * @param string $format Default 'Y-m-d'
     * @return $this
     */
    public function groupByDate($format = '%Y-%m-%d')
    {
        $this->getSelect()->group("DATE_FORMAT(created_at, '{$format}')");
        return $this;
    }

    /**
     * Add total value calculations to select
     *
     * @return $this
     */
    public function addTotalValueToSelect()
    {
        $this->getSelect()->columns([
            'total_debits' => 'SUM(CASE WHEN value_change < 0 THEN ABS(value_change) ELSE 0 END)',
            'total_credits' => 'SUM(CASE WHEN value_change > 0 THEN value_change ELSE 0 END)',
            'net_change' => 'SUM(value_change)',
            'transaction_count' => 'COUNT(*)'
        ]);
        return $this;
    }

    /**
     * Get usage statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        $clone = $this->_cloneCollection();
        $clone->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $clone->getSelect()->columns([
            'total_transactions' => 'COUNT(*)',
            'total_debits' => 'SUM(CASE WHEN value_change < 0 THEN 1 ELSE 0 END)',
            'total_credits' => 'SUM(CASE WHEN value_change > 0 THEN 1 ELSE 0 END)',
            'total_debit_amount' => 'SUM(CASE WHEN value_change < 0 THEN ABS(value_change) ELSE 0 END)',
            'total_credit_amount' => 'SUM(CASE WHEN value_change > 0 THEN value_change ELSE 0 END)',
            'net_amount' => 'SUM(value_change)',
            'average_transaction' => 'AVG(ABS(value_change))',
            'max_transaction' => 'MAX(ABS(value_change))',
            'min_transaction' => 'MIN(ABS(value_change))',
            'unique_gift_cards' => 'COUNT(DISTINCT gift_card_id)',
            'unique_orders' => 'COUNT(DISTINCT order_id)'
        ]);

        return $clone->getConnection()->fetchRow($clone->getSelect());
    }

    /**
     * Get daily usage summary
     *
     * @return $this
     */
    public function getDailySummary()
    {
        $this->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $this->getSelect()->columns([
            'usage_date' => 'DATE(created_at)',
            'transaction_count' => 'COUNT(*)',
            'total_amount' => 'SUM(ABS(value_change))',
            'debit_count' => 'SUM(CASE WHEN value_change < 0 THEN 1 ELSE 0 END)',
            'credit_count' => 'SUM(CASE WHEN value_change > 0 THEN 1 ELSE 0 END)',
            'debit_amount' => 'SUM(CASE WHEN value_change < 0 THEN ABS(value_change) ELSE 0 END)',
            'credit_amount' => 'SUM(CASE WHEN value_change > 0 THEN value_change ELSE 0 END)',
            'unique_gift_cards' => 'COUNT(DISTINCT gift_card_id)',
            'unique_orders' => 'COUNT(DISTINCT order_id)'
        ]);
        $this->groupByDate();
        $this->setOrder('usage_date', 'DESC');
        return $this;
    }

    /**
     * Get monthly usage summary
     *
     * @return $this
     */
    public function getMonthlySummary()
    {
        $this->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $this->getSelect()->columns([
            'usage_month' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'transaction_count' => 'COUNT(*)',
            'total_amount' => 'SUM(ABS(value_change))',
            'debit_count' => 'SUM(CASE WHEN value_change < 0 THEN 1 ELSE 0 END)',
            'credit_count' => 'SUM(CASE WHEN value_change > 0 THEN 1 ELSE 0 END)',
            'debit_amount' => 'SUM(CASE WHEN value_change < 0 THEN ABS(value_change) ELSE 0 END)',
            'credit_amount' => 'SUM(CASE WHEN value_change > 0 THEN value_change ELSE 0 END)',
            'unique_gift_cards' => 'COUNT(DISTINCT gift_card_id)',
            'unique_orders' => 'COUNT(DISTINCT order_id)'
        ]);
        $this->groupByDate('%Y-%m');
        $this->setOrder('usage_month', 'DESC');
        return $this;
    }

    /**
     * Get usage by gift card with totals
     *
     * @return $this
     */
    public function getUsageByGiftCard()
    {
        $this->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $this->getSelect()->columns([
            'gift_card_id',
            'transaction_count' => 'COUNT(*)',
            'total_usage' => 'SUM(ABS(value_change))',
            'net_change' => 'SUM(value_change)',
            'first_usage' => 'MIN(created_at)',
            'last_usage' => 'MAX(created_at)'
        ]);
        $this->groupByGiftCard();
        return $this;
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

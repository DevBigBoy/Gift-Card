<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\AbstractModel;

/**
 * Gift Card Usage Resource Model
 */
class GiftCardUsage extends AbstractDb
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        DateTime $date,
        $connectionName = null
    ) {
        $this->date = $date;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gift_card_usage', 'id');
    }

    /**
     * Process gift card usage data before saving
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object)
    {
        // Set created_at timestamp
        if (!$object->getId()) {
            $object->setCreatedAt($this->date->gmtDate());
        }

        return parent::_beforeSave($object);
    }

    /**
     * Get usage history for a specific gift card
     *
     * @param int $giftCardId
     * @param int|null $limit
     * @return array
     */
    public function getUsageHistoryByGiftCardId($giftCardId, $limit = null)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('gift_card_id = ?', $giftCardId)
            ->order('created_at DESC');

        if ($limit) {
            $select->limit($limit);
        }

        return $connection->fetchAll($select);
    }

    /**
     * Get usage history for a specific order
     *
     * @param int $orderId
     * @return array
     */
    public function getUsageHistoryByOrderId($orderId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('order_id = ?', $orderId)
            ->order('created_at ASC');

        return $connection->fetchAll($select);
    }

    /**
     * Get total usage amount for a gift card
     *
     * @param int $giftCardId
     * @return float
     */
    public function getTotalUsageByGiftCardId($giftCardId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'SUM(value_change)')
            ->where('gift_card_id = ?', $giftCardId);

        $result = $connection->fetchOne($select);
        return (float)($result ?: 0);
    }

    /**
     * Get usage statistics for a specific gift card
     *
     * @param int $giftCardId
     * @return array
     */
    public function getUsageStatistics($giftCardId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                $this->getMainTable(),
                [
                    'total_transactions' => 'COUNT(*)',
                    'total_debits' => 'SUM(CASE WHEN value_change < 0 THEN 1 ELSE 0 END)',
                    'total_credits' => 'SUM(CASE WHEN value_change > 0 THEN 1 ELSE 0 END)',
                    'total_debit_amount' => 'SUM(CASE WHEN value_change < 0 THEN ABS(value_change) ELSE 0 END)',
                    'total_credit_amount' => 'SUM(CASE WHEN value_change > 0 THEN value_change ELSE 0 END)',
                    'net_change' => 'SUM(value_change)',
                    'first_usage' => 'MIN(created_at)',
                    'last_usage' => 'MAX(created_at)'
                ]
            )
            ->where('gift_card_id = ?', $giftCardId);

        return $connection->fetchRow($select);
    }

    /**
     * Get overall usage statistics
     *
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function getOverallUsageStatistics($fromDate = null, $toDate = null)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                $this->getMainTable(),
                [
                    'total_transactions' => 'COUNT(*)',
                    'unique_gift_cards' => 'COUNT(DISTINCT gift_card_id)',
                    'unique_orders' => 'COUNT(DISTINCT order_id)',
                    'total_amount_used' => 'SUM(ABS(value_change))',
                    'average_transaction' => 'AVG(ABS(value_change))',
                    'max_transaction' => 'MAX(ABS(value_change))',
                    'min_transaction' => 'MIN(ABS(value_change))'
                ]
            );

        if ($fromDate) {
            $select->where('created_at >= ?', $fromDate);
        }

        if ($toDate) {
            $select->where('created_at <= ?', $toDate);
        }

        return $connection->fetchRow($select);
    }

    /**
     * Get gift card usage by date range
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int|null $limit
     * @return array
     */
    public function getUsageByDateRange($fromDate, $toDate, $limit = null)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('created_at >= ?', $fromDate)
            ->where('created_at <= ?', $toDate)
            ->order('created_at DESC');

        if ($limit) {
            $select->limit($limit);
        }

        return $connection->fetchAll($select);
    }

    /**
     * Get daily usage summary
     *
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    public function getDailyUsageSummary($fromDate, $toDate)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                $this->getMainTable(),
                [
                    'usage_date' => 'DATE(created_at)',
                    'transaction_count' => 'COUNT(*)',
                    'total_amount' => 'SUM(ABS(value_change))',
                    'unique_gift_cards' => 'COUNT(DISTINCT gift_card_id)',
                    'unique_orders' => 'COUNT(DISTINCT order_id)'
                ]
            )
            ->where('created_at >= ?', $fromDate)
            ->where('created_at <= ?', $toDate)
            ->group('DATE(created_at)')
            ->order('usage_date DESC');

        return $connection->fetchAll($select);
    }

    /**
     * Check if a gift card has been used in a specific order
     *
     * @param int $giftCardId
     * @param int $orderId
     * @return bool
     */
    public function hasUsageInOrder($giftCardId, $orderId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'id')
            ->where('gift_card_id = ?', $giftCardId)
            ->where('order_id = ?', $orderId)
            ->limit(1);

        $result = $connection->fetchOne($select);
        return (bool)$result;
    }
}

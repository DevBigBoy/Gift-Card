<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Gift Card Resource Model
 */
class GiftCard extends AbstractDb
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
        $this->_init('gift_card', 'id');
    }

    /**
     * Process gift card data before saving
     *
     * @param AbstractModel $object
     * @return $this
     */
    //    protected function _beforeSave(AbstractModel $object)
    //    {
    //        // Set updated_at timestamp
    //        $object->setUpdatedAt($this->date->gmtDate());
    //
    //        // Set created_at timestamp for new records
    //        if (!$object->getId()) {
    //            $object->setCreatedAt($this->date->gmtDate());
    //        }
    //
    //        return parent::_beforeSave($object);
    //    }

    /**
     * Check if gift card code already exists
     *
     * @param string $code
     * @param int|null $excludeId
     * @return bool
     */
    //    public function isCodeExists($code, $excludeId = null)
    //    {
    //        $connection = $this->getConnection();
    //        $select = $connection->select()
    //            ->from($this->getMainTable(), 'id')
    //            ->where('code = ?', $code);
    //
    //        if ($excludeId) {
    //            $select->where('id != ?', $excludeId);
    //        }
    //
    //        $result = $connection->fetchOne($select);
    //        return (bool)$result;
    //    }

    /**
     * Get gift cards by customer ID
     *
     * @param int $customerId
     * @return array
     */
    //    public function getGiftCardsByCustomerId($customerId)
    //    {
    //        $connection = $this->getConnection();
    //        $select = $connection->select()
    //            ->from($this->getMainTable())
    //            ->where('assigned_customer_id = ?', $customerId)
    //            ->order('created_at DESC');
    //
    //        return $connection->fetchAll($select);
    //    }

    /**
     * Get active gift cards by customer ID
     *
     * @param int $customerId
     * @return array
     */
    //    public function getActiveGiftCardsByCustomerId($customerId)
    //    {
    //        $connection = $this->getConnection();
    //        $select = $connection->select()
    //            ->from($this->getMainTable())
    //            ->where('assigned_customer_id = ?', $customerId)
    //            ->where('status = ?', \SwiftOtter\GiftCard\Api\Data\GiftCardInterface::STATUS_ACTIVE)
    //            ->where('current_value > ?', 0)
    //            ->order('created_at DESC');
    //
    //        return $connection->fetchAll($select);
    //    }

    /**
     * Update gift card balance
     *
     * @param int $giftCardId
     * @param float $newBalance
     * @return bool
     */
    //    public function updateBalance($giftCardId, $newBalance)
    //    {
    //        $connection = $this->getConnection();
    //
    //        $result = $connection->update(
    //            $this->getMainTable(),
    //            [
    //                'current_value' => $newBalance,
    //                'updated_at' => $this->date->gmtDate()
    //            ],
    //            ['id = ?' => $giftCardId]
    //        );
    //
    //        return $result > 0;
    //    }

    /**
     * Get gift card statistics
     *
     * @return array
     */
    //    public function getGiftCardStatistics()
    //    {
    //        $connection = $this->getConnection();
    //
    //        $select = $connection->select()
    //            ->from(
    //                $this->getMainTable(),
    //                [
    //                    'total_cards' => 'COUNT(*)',
    //                    'active_cards' => 'SUM(CASE WHEN status = ' . \SwiftOtter\GiftCard\Api\Data\GiftCardInterface::STATUS_ACTIVE . ' THEN 1 ELSE 0 END)',
    //                    'total_initial_value' => 'SUM(initial_value)',
    //                    'total_current_value' => 'SUM(current_value)',
    //                    'average_initial_value' => 'AVG(initial_value)',
    //                    'average_current_value' => 'AVG(current_value)'
    //                ]
    //            );
    //
    //        return $connection->fetchRow($select);
    //    }

    /**
     * Get expired gift cards that need status update
     *
     * @param string $expirationDate
     * @return array
     */
    //    public function getExpiredGiftCards($expirationDate)
    //    {
    //        $connection = $this->getConnection();
    //        $select = $connection->select()
    //            ->from($this->getMainTable(), 'id')
    //            ->where('status = ?', \SwiftOtter\GiftCard\Api\Data\GiftCardInterface::STATUS_ACTIVE)
    //            ->where('created_at < ?', $expirationDate);
    //
    //        return $connection->fetchCol($select);
    //    }

    /**
     * Bulk update gift card statuses
     *
     * @param array $giftCardIds
     * @param int $status
     * @return bool
     */
    //    public function bulkUpdateStatus(array $giftCardIds, $status)
    //    {
    //        if (empty($giftCardIds)) {
    //            return false;
    //        }
    //
    //        $connection = $this->getConnection();
    //
    //        $result = $connection->update(
    //            $this->getMainTable(),
    //            [
    //                'status' => $status,
    //                'updated_at' => $this->date->gmtDate()
    //            ],
    //            ['id IN (?)' => $giftCardIds]
    //        );
    //
    //        return $result > 0;
    //    }
}

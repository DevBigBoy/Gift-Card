<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Gift Card Resource Model
 */
class GiftCard extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gift_card', 'id');
    }
}

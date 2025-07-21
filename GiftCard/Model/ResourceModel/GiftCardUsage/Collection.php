<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\GiftCard\Model\GiftCardUsage as GiftCardUsageModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResourceModel;

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
            GiftCardUsageModel::class,
            GiftCardUsageResourceModel::class
        );
    }


}

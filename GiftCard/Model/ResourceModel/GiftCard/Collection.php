<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\GiftCard\Model\GiftCard as GiftCardModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResourceModel;

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
            GiftCardModel::class,
            GiftCardResourceModel::class
        );
    }

}

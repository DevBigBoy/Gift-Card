<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for GiftCard search results.
 * @api
 * @since 100.0.2
 */
interface GiftCardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get GiftCard list.
     *
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[]
     */
    public function getItems();

    /**
     * Set GiftCard list.
     *
     * @param \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[] $items
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface
     */
    public function setItems(array $items);
}

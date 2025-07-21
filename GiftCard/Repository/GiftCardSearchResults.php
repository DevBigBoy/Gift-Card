<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SwiftOtter\GiftCard\Repository;

use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with GiftCard search results.
 */
class GiftCardSearchResults extends SearchResults implements GiftCardSearchResultsInterface
{
}

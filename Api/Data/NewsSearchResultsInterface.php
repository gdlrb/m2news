<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface NewsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get news list.
     *
     * @return \Aheadworks\News\Api\Data\NewsInterface[]
     */
    public function getItems();

    /**
     * Set news list.
     *
     * @param \Aheadworks\News\Api\Data\NewsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

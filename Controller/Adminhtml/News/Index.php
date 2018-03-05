<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Controller\Adminhtml\News;

use Aheadworks\News\Controller\Adminhtml\News;

/**
 * Class Index
 * @package Aheadworks\Data\Controller\Adminhtml\Data
 */
class Index extends News
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}

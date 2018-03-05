<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Controller\Adminhtml\News;

use Aheadworks\News\Controller\Adminhtml\News;

class Edit extends News
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $dataId = $this->getRequest()->getParam('id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Aheadworks_News::news')
            ->addBreadcrumb(__('News'), __('News'))
            ->addBreadcrumb(__('Manage News'), __('Manage News'));

        if ($dataId === null) {
            $resultPage->addBreadcrumb(__('New News'), __('New News'));
            $resultPage->getConfig()->getTitle()->prepend(__('New News'));
        } else {
            $resultPage->addBreadcrumb(__('Edit News'), __('Edit News'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->newsRepository->getById($dataId)->getDataTitle()
            );
        }
        return $resultPage;
    }
}

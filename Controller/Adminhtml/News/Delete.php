<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Controller\Adminhtml\News;

use Aheadworks\News\Controller\Adminhtml\News;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends News
{
    /**
     * Delete the data entity
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $dataId = $this->getRequest()->getParam('id');
        if ($dataId) {
            try {
                $this->newsRepository->deleteById($dataId);
                $this->messageManager->addSuccessMessage(__('The data has been deleted.'));
                $resultRedirect->setPath('news/news/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The data no longer exists.'));
                return $resultRedirect->setPath('news/news/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('news/news/index', ['id' => $dataId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the data'));
                return $resultRedirect->setPath('news/news/edit', ['id' => $dataId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the data to delete.'));
        $resultRedirect->setPath('news/news/index');
        return $resultRedirect;
    }
}

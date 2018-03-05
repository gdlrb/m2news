<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Controller\Adminhtml\News;

use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\News\Api\NewsRepositoryInterface;
use Aheadworks\News\Api\Data\NewsInterface;
use Aheadworks\News\Api\Data\NewsInterfaceFactory;
use Aheadworks\News\Controller\Adminhtml\News;

class Save extends News
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var NewsRepositoryInterface
     */
    protected $dataRepository;

    /**
     * @var NewsInterfaceFactory
     */
    protected $dataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Save constructor.
     * @param Registry $registry
     * @param NewsRepositoryInterface $dataRepository
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Manager $messageManager
     * @param NewsInterfaceFactory $dataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param Context $context
     * @param DateTime $dateTime
     */
    public function __construct(
        Registry $registry,
        NewsRepositoryInterface $dataRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        NewsInterfaceFactory $dataFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        DateTime $dateTime
    ) {
        $this->messageManager   = $messageManager;
        $this->dataFactory      = $dataFactory;
        $this->dataRepository   = $dataRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($registry, $dataRepository, $resultPageFactory, $resultForwardFactory, $context, $dateTime);
    }

    /**
     * Save action
     */
    public function execute()
    {
        $isPost = $this->getRequest()->getPostValue();

        if($isPost) {
            $newsId = $this->getRequest()->getParam('id');
            $formData = $this->getRequest()->getPostValue();
            $formData = $this->preparePostData($formData);
            try {
                $newsDataObject = $newsId
                    ? $this->newsRepository->getById($newsId)
                    : $this->dataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $newsDataObject,
                    $formData,
                    NewsInterface::class
                );
                $news = $this->newsRepository->save($newsDataObject);
                $this->messageManager->addSuccess(__('The news has been saved.'));

                if($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $news->getId(), '_current'=>true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch(\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $newsId]);
        }
    }
}

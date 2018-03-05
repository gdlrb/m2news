<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Controller\Adminhtml;

use Aheadworks\News\Api\Data\NewsInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Aheadworks\News\Api\NewsRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

abstract class News extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Aheadworks_News::news';

    /**
     * Data repository
     *
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result Forward Factory
     *
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Data constructor.
     * @param Registry $registry
     * @param NewsRepositoryInterface $newsRepository
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Context $context
     * @param DateTime $dateTime
     */
    public function __construct(
        Registry $registry,
        NewsRepositoryInterface $newsRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Context $context,
        DateTime $dateTime
    ) {
        $this->coreRegistry         = $registry;
        $this->newsRepository       = $newsRepository;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->dateTime             = $dateTime;
        parent::__construct($context);
    }

    /**
     * Prepare news data for save
     *
     * @param array $formData
     * @return array
     */
    protected function preparePostData(array $formData)
    {
        $formData[NewsInterface::NEWS_DATE] = $this->getPreparedPublishDate($formData[NewsInterface::NEWS_DATE]);
        return $formData;
    }

    /**
     * Get prepared publish date
     *
     * @param array $formData
     * @return string
     */
    private function getPreparedPublishDate($date)
    {
        $publishDate = $this->dateTime->gmtDate(
            \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT
        );
        if (!empty($date)) {
            $publishDateTimestamp = strtotime($date);
            $publishDate = $this->dateTime->gmtDate(
                \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT,
                $publishDateTimestamp
            );
        }
        return $publishDate;
    }
}

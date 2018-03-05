<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Block;

use Aheadworks\News\Model\News\Source\Status;
use Magento\Framework\View\Element\Template;
use Aheadworks\News\Model\NewsFactory;

class News extends Template
{
    const LIMIT_PAGER = 10;

    protected $_newsFactory;

    /**
     * Data constructor.
     * @param Template\Context $context
     * @param NewsFactory $newsFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        NewsFactory $newsFactory,
        array $data = []
    ) {
        $this->_newsFactory = $newsFactory;
        parent::__construct($context, $data);
    }

    protected function _construct() {
        parent::_construct();
        $collection = $this->_newsFactory
            ->create()
            ->getCollection()
            ->addStatusFilter(Status::ENABLED)
            ->setOrder('date', 'DESC');
        $this->setCollection($collection);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->getBlock('aw_news');
        $pager->setLimit($this::LIMIT_PAGER)->setShowAmount(false)->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }
}

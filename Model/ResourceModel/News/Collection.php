<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model\ResourceModel\News;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Aheadworks\News\Model\News;
use Aheadworks\News\Model\ResourceModel\News as ResourceNews;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @param EntityFactoryInterface $entityFactory,
     * @param LoggerInterface        $logger,
     * @param FetchStrategyInterface $fetchStrategy,
     * @param ManagerInterface       $eventManager,
     * @param StoreManagerInterface  $storeManager,
     * @param AdapterInterface       $connection,
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->_init(News::class, ResourceNews::class);
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    /**
     * Add filter by status to collection
     *
     * @param int $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        return $this->addFieldToFilter('aw_news_linkage_table.status', ['eq' => $status]);
    }

    /**
     * @return $this|void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('id', 'main_table.id');
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['aw_news_linkage_table' => $this->getTable('aw_news_store')],
            'main_table.id = aw_news_linkage_table.news_item_id',
            ['news_item_id', 'store_id', 'status']
        );
    }
}

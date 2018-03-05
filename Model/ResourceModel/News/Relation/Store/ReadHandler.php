<?php
/**
 * Copyright 2016 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model\ResourceModel\News\Relation\Store;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\News\Api\Data\NewsInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 * @package Aheadworks\Data\Model\ResourceModel\Data\Relation\Store
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(MetadataPool $metadataPool, ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entityId = (int)$entity->getId()) {
            $connection = $this->resourceConnection->getConnectionByName(
                $this->metadataPool->getMetadata(NewsInterface::class)->getEntityConnectionName()
            );
            $select = $connection->select()
                ->joinLeft(
                    ['aw_news_linkage_table' => $this->resourceConnection->getTableName('aw_news_store')],
                    'main_table.id = aw_news_linkage_table.news_item_id',
                    ['news_item_id', 'store_id', 'status']
                )
                ->where('aw_news_linkage_table.news_item_id = :id', $entityId);
            $newsIds = $connection->fetchCol($select, ['id' => $entityId]);
            $entity->setId($newsIds);
        }
        return $entity;
    }
}

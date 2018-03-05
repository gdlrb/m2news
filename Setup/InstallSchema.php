<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Aheadworks\Data\Setup
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;

        $installer->startSetup();

        $tableName = $installer->getTable('aw_news');

        if($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Title'
                )
                ->addColumn(
                    'date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Date'
                )
                ->setComment('Data Table');
            $installer->getConnection()->createTable($table);
        }

        $tableName = $installer->getTable('aw_news_store');

        if($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'news_item_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Data item id'
                )
                ->addColumn(
                    'store_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Store id'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Status'
                )
                ->setComment('Data Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}

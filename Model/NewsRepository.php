<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\News\Api\NewsRepositoryInterface;
use Aheadworks\News\Api\Data\NewsInterface;
use Aheadworks\News\Api\Data\NewsInterfaceFactory;
use Aheadworks\News\Api\Data\NewsSearchResultsInterfaceFactory;
use Aheadworks\News\Model\NewsFactory;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Reflection\DataObjectProcessor;
use Aheadworks\News\Model\ResourceModel\News as ResourceNews;
use Aheadworks\News\Model\ResourceModel\News\CollectionFactory as NewsCollectionFactory;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var ResourceNews
     */
    protected $resource;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var NewsFactory
     */
    private $newsFactory;

    /**
     * @var NewsCollectionFactory
     */
    protected $newsCollectionFactory;

    /**
     * @var NewsSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var NewsInterfaceFactory
     */
    protected $newsInterfaceFactory;

    /**
     * @var NewsRegistry
     */
    private $newsRegistry;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * NewsRepository constructor.
     * @param ResourceNews $resource
     * @param EntityManager $entityManager
     * @param \Aheadworks\News\Model\NewsFactory $newsFactory
     * @param NewsCollectionFactory $newsCollectionFactory
     * @param NewsSearchResultsInterfaceFactory $newsSearchResultsInterfaceFactory
     * @param NewsInterfaceFactory $newsInterfaceFactory
     * @param NewsRegistry $newsRegistry
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        ResourceNews $resource,
        EntityManager $entityManager,
        NewsFactory $newsFactory,
        NewsCollectionFactory $newsCollectionFactory,
        NewsSearchResultsInterfaceFactory $newsSearchResultsInterfaceFactory,
        NewsInterfaceFactory $newsInterfaceFactory,
        NewsRegistry $newsRegistry,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->entityManager = $entityManager;
        $this->newsFactory = $newsFactory;
        $this->newsCollectionFactory = $newsCollectionFactory;
        $this->searchResultsFactory = $newsSearchResultsInterfaceFactory;
        $this->newsInterfaceFactory = $newsInterfaceFactory;
        $this->newsRegistry = $newsRegistry;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws \Exception
     */
    public function save(NewsInterface $news)
    {
        /** @var \Aheadworks\News\Model\News $newsModel */
        $newsModel = $this->newsFactory->create();
        if ($newsId = $news->getId()) {
            $this->entityManager->load($newsModel, $newsId);
        }
        $newsModel->addData(
            $this->dataObjectProcessor->buildOutputDataArray($news, NewsInterface::class)
        );
        $this->entityManager->save($newsModel);
        $news = $this->getCategoryDataObject($newsModel);
        $this->newsRegistry->push($news);

        return $news;
    }

    /**
     * Get news record
     *
     * @param $newsId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($newsId)
    {
        if (!isset($this->instances[$newsId])) {
            /** @var \Aheadworks\News\Api\Data\NewsInterface|\Magento\Framework\Model\AbstractModel $news */
            $news = $this->newsInterfaceFactory->create();
            $this->resource->load($news, $newsId);
            if (!$news->getId()) {
                throw new NoSuchEntityException(__('Requested data doesn\'t exist'));
            }
            $this->instances[$newsId] = $news;
        }
        return $this->instances[$newsId];
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\News\Api\Data\NewsSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\News\Api\Data\NewsSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Aheadworks\News\Model\ResourceModel\News\Collection $collection */
        $collection = $this->newsCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $field = 'date';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $news = [];
        foreach ($collection as $datum) {
            $newsDataObject = $this->newsInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($newsDataObject, $datum->getData(), NewsInterface::class);
            $news[] = $newsDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($news);
    }

    /**
     * @param NewsInterface $news
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(NewsInterface $news)
    {
        /** @var \Aheadworks\News\Api\Data\NewsInterface|\Magento\Framework\Model\AbstractModel $news */
        $id = $news->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($news);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * @param $newsId
     * @return bool|mixed
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($newsId)
    {
        $news = $this->getById($newsId);
        return $this->delete($news);
    }

    /**
     * Creates category data object using Category Model
     *
     * @param \Aheadworks\News\Model\News $news
     * @return NewsInterface
     */
    private function getCategoryDataObject(\Aheadworks\News\Model\News $news)
    {
        /** @var NewsInterface $categoryDataObject */
        $categoryDataObject = $this->newsFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $categoryDataObject,
            $news->getData(),
            NewsInterface::class
        );
        return $categoryDataObject;
    }
}

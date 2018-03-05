<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Aheadworks\News\Api\Data\NewsInterface;

interface NewsRepositoryInterface
{

    /**
     * @param NewsInterface $news
     * @return mixed
     */
    public function save(NewsInterface $news);


    /**
     * @param $newsId
     * @return mixed
     */
    public function getById($newsId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param NewsInterface $news
     * @return mixed
     */
    public function delete(NewsInterface $news);

    /**
     * @param $newsId
     * @return mixed
     */
    public function deleteById($newsId);
}

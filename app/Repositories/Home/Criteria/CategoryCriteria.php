<?php
namespace App\Repositories\Home\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class CategoryCriteria extends Criteria {

    private $conditions;

    public function __construct($conditions){
        $this->conditions = $conditions;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {

        $model = $model->orderBy('id','ASC');

        return $model;
    }
}
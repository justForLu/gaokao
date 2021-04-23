<?php
namespace App\Repositories\Home\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface as Repository;

class ArticleCriteria implements CriteriaInterface {

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
        if (isset($this->conditions['category_id']) && !empty($this->conditions['category_id'])){
            $model = $model->where('category_id', $this->conditions['category_id']);
        }

        $model = $model->orderBy('is_top','DESC');
        $model = $model->orderBy('is_recommend','DESC');
        $model = $model->orderBy('sort','ASC');
        $model = $model->orderBy('id','ASC');

        return $model;
    }
}

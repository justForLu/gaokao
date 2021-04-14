<?php
namespace App\Repositories\Admin\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface as Repository;

class PermissionCriteria implements CriteriaInterface {

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
        if(isset($this->conditions['module']) && !empty($this->conditions['module'])){
            $model = $model->where('module', $this->conditions['module']);
        }

        if(isset($this->conditions['name'])){
            $model = $model->where('name', 'like','%' . $this->conditions['name'] . '%');
        }

        $model = $model->orderBy('id','DESC');

        return $model;
    }
}

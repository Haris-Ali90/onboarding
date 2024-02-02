<?php

namespace App\Repositories;
use App\Models\Interfaces\CustomerFlagCategoryInterface;
use App\Models\Interfaces\CustomerFlagCategoryPortalsInterface;
use App\Repositories\Interfaces\CustomerFlagCategoryPortalsRepositoryInterface;



class CustomerFlagCategoryPortalsRepository implements CustomerFlagCategoryPortalsRepositoryInterface
{
    private $model;

    public function __construct(CustomerFlagCategoryPortalsInterface $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function create(array $data)
    {
        $model = $this->model::create($data);
        return $model;
    }

    public function insert(array $data)
    {
        $model = $this->model::insert($data);
        return $model;
    }

    public function find($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function findBy($attribute, $value) {
        return $this->model->where($attribute, '=', $value)->first();
    }

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }

    public function deletePortal($id)
    {
        $this->model::where('category_ref_id', $id)->delete();
    }


}

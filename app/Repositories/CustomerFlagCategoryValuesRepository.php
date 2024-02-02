<?php

namespace App\Repositories;
use App\Models\Interfaces\CustomerFlagCategoryValuesInterface;
use App\Repositories\Interfaces\CustomerFlagCategoryValuesRepositoryInterface;


class CustomerFlagCategoryValuesRepository implements CustomerFlagCategoryValuesRepositoryInterface
{
    private $model;

    public function __construct(CustomerFlagCategoryValuesInterface $model)
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

    public function find($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function updateOrCreateByCategory($id,array $data)
    {
        return $this->model::updateOrCreate(['category_ref_id'=>$id],$data);
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

    public function deleteCategoryValue($id)
    {
        $this->model::where('category_ref_id', $id)->delete();
    }


}

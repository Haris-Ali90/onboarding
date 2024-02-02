<?php

namespace App\Repositories;

use App\Models\Interfaces\SlotPostalCodeInterface;
use App\Repositories\Interfaces\SlotPostalCodeRepositoryInterface;

class SlotPostalCodeRepository implements SlotPostalCodeRepositoryInterface
{
    private $model;

    public function __construct(SlotPostalCodeInterface $model)
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

    public function updateOrCreate($id,array $data)
    {
        return $this->model::updateOrCreate(['hub_id'=>$id],$data);
    }

    public function deletePostalCode($id)
    {
        //dd($id);
        $this->model::where('zone_id', $id)->delete();
    }


}

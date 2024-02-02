<?php

namespace App\Repositories;

use App\Models\Interfaces\OrderCategoryInterface;
use App\Repositories\Interfaces\OrderCategoryRepositoryInterface;

/**
 * Class SiteSettingRepository
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   05/10/18
 */
class OrderCategoryRepository implements OrderCategoryRepositoryInterface
{
    private $model;

    public function __construct(OrderCategoryInterface $model)
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

    public function findFirst()
    {
        return $this->model::first();
    }

    public function find($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }
}

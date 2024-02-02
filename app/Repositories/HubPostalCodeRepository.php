<?php

namespace App\Repositories;

use App\Models\Interfaces\HubPostalCodeInterface;
use App\Repositories\Interfaces\HubPostalCodeRepositoryInterface;

/**
 * Class SiteSettingRepository
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   05/10/18
 */
class HubPostalCodeRepository implements HubPostalCodeRepositoryInterface
{
    private $model;

    public function __construct(HubPostalCodeInterface $model)
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

    public function deletePostalCode($id)
    {
        $this->model::where('hub_id', $id)->delete();
    }
}

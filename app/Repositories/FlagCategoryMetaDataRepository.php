<?php

namespace App\Repositories;

use App\Models\Interfaces\CustomerFlagCategoryInterface;
use App\Models\Interfaces\FlagCategoryMetaDataInterface;
use App\Repositories\Interfaces\FlagCategoryMetaDataRepositoryInterface;

class FlagCategoryMetaDataRepository implements FlagCategoryMetaDataRepositoryInterface
{
    private $model;

    private $flag_meta_types = [];

    public function __construct(FlagCategoryMetaDataInterface $model)
    {
        $this->model = $model;
        $this->flag_meta_types = $this->model::$flag_meta_types;
    }

    public function getFlagMetaTypes($arrg = [])
    {
        $data = $this->flag_meta_types;
        $arrg_type = gettype($arrg);
        if ($arrg_type == 'array') {
            $data = array_intersect($data, $arrg);
        } elseif ($arrg_type == 'string') {
            $data = $this->flag_meta_types[$arrg];
        }
        return $data;
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

    public function findBy($attribute, $value)
    {
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


    public function MultiDataSave($cat_id, $data_unformatted)
    {
        $formatted_data = [];
        $current_DateTime = date('Y-m-d H:i:s');
        $types = array_keys($data_unformatted);
        // looping on data to format
        foreach ($data_unformatted as $type => $single_data) {
            // looping on the values
            foreach ($single_data as $data) {
                $formatted_data[] =
                    [
                        'category_ref_id' => $cat_id,
                        'type' => $type,
                        'value' => $data,
                        'created_at' => $current_DateTime,
                    ];
            }

        }

        // saving data
        $model = $this->model::insert($formatted_data);

        // getting current saving data
        $saved_data = $this->model::where('created_at', $current_DateTime)
            ->whereIn('type', $types)
            ->where('category_ref_id', $cat_id)
            ->get();


        return $saved_data;
    }


    public function MultiDataSync($cat_id, $data_unformatted)
    {
        $formatted_data = [];
        $current_DateTime = date('Y-m-d H:i:s');
        $types = array_keys($data_unformatted);

        // soft deleting old meta data for add new one
        $this->model::where('category_ref_id', $cat_id)
            ->whereIn('type', $types)
            ->update(['deleted_at' => $current_DateTime]);

        // looping on data to format
        foreach ($data_unformatted as $type => $single_data) {
            // looping on the values
            foreach ($single_data as $data) {
                $formatted_data[] =
                    [
                        'category_ref_id' => $cat_id,
                        'type' => $type,
                        'value' => $data,
                        'created_at' => $current_DateTime,
                    ];
            }

        }

        // saving data
        $model = $this->model::insert($formatted_data);

        // getting current saving data
        $saved_data = $this->model::where('created_at', $current_DateTime)
            ->whereIn('type', $types)
            ->where('category_ref_id', $cat_id)
            ->get();


        return $saved_data;
    }


}

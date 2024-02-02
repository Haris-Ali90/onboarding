<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RepositoryInterface
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   29/11/18
 */
interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);



}

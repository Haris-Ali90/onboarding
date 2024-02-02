<?php

namespace App\Repositories;


use App\Models\Interfaces\UserInterface;
use App\Models\User;
use App\Models\Verification;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   05/10/18
 */
class UserRepository implements UserRepositoryInterface
{
    private $model;

    public function __construct(UserInterface $model)
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

    public function checkSmsCode($email,$code)
    {
        return Verification::where([
            'code'        => $code,
            'email'       => $email,
        ])->first();
    }

    public function updateSmsVerification($email)
    {
        // $user = $this->model->where('phone',$phone)->first();
        User::where('email',$email)->update(['email_verified' => 1, 'is_active' => 1]);
        Verification::where('email',$email)->delete();
    }

    public function resendMessage($email,$code,$id)
    {
        Verification::where('email', $email)->delete();
        Verification::create([
            'email' => $email,
            'code' => $code,
            'user_id' => $id,
        ]);
    }
}

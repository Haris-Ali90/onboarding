<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAdministratorRequest;
use Auth;
use Illuminate\Http\Response;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    private $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->adminRepository = $adminRepository;
    }

    public function changePassword()
    {
        return view('admin.users.changePassword');
    }

    public function processChangePassword(UpdatePasswordRequest $request)
    {
        $id = Auth::user()->id;
        if(Hash::check($request->get('oldPassword'),Auth::user()->password)){
            $data['password'] = bcrypt($request->get('password'));
            $this->adminRepository->update($id, $data);
            return redirect()
                ->route('users.change-password')
                ->with('success', 'Password has been changed successfully..');
        }else{
            return redirect()
                ->route('users.change-password')
                ->with('success', 'Please enter the old password correctly');
        }

    }

    public function editProfile()
    {
        $data = $this->adminRepository->find(auth()->user()->id);
        return view('admin.users.profile', compact('data'));
    }

    public function updateEditProfile(UpdateAdministratorRequest $request)
    {
        $userRecord = auth()->user();
        $exceptFields = [
            '_token',
            '_method',
            'email',
        ];

        // 1 = super admin user id, and is_active status cannot be set for it
        if ($userRecord->id == 1) {
            $exceptFields[] = 'is_active';
        }
        $data = $request->except($exceptFields);

        $updateRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name']. ' ' .$data['last_name'],
            'user_name' => strtolower($data['first_name'].$data['last_name']),
            'phone' => phoneFormat($data['phone']),
        ];

        //check logo if exists
        if ($request->hasfile('profile_picture')) {
            //move | upload file on server
            $slug = Str::slug($data['first_name'] . ' ' . $data['last_name'], '-');
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = $slug . '-' . time() . '.' . $extension;

            $file->move(backendUserFile(), $filename);

            $updateRecord['profile_picture'] = url(backendUserFile(),$filename);
            $oldImage = $userRecord->profile_picture;

        }
        if (isset($data['password']))
        {
            $updateRecord['password'] = bcrypt($data['password']);
        }
        $this->adminRepository->update($userRecord->id, $updateRecord);

        if (isset($oldImage)) {

            $this->safeRemoveImage($oldImage, backendUserFile());

        }

        return redirect()
            ->route('users.edit-profile')
            ->with('success', 'Profile updated successfully.');
    }

    //Micro Hub User Edit Profile
    /**
     * Get Function For Edit Profile
     *
     */
    public function microHubEditProfile()
    {
        $data = $this->adminRepository->find(auth()->user()->id);
        return view('admin.micro-hub.users.profile', compact('data'));
    }

    /**
     * Post Function For Edit Profile
     *
     */
    public function microHubUpdateEditProfile(UpdateAdministratorRequest $request)
    {
        $userRecord = auth()->user();
        $exceptFields = [
            '_token',
            '_method',
            'email',
        ];

        // 1 = super admin user id, and is_active status cannot be set for it
        if ($userRecord->id == 1) {
            $exceptFields[] = 'is_active';
        }
        $data = $request->except($exceptFields);

        $updateRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name']. ' ' .$data['last_name'],
            'user_name' => strtolower($data['first_name'].$data['last_name']),
            'phone' => phoneFormat($data['phone']),
        ];

        //check logo if exists
        if ($request->hasfile('profile_picture')) {
            //move | upload file on server
            $slug = Str::slug($data['first_name'] . ' ' . $data['last_name'], '-');
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = $slug . '-' . time() . '.' . $extension;

            $file->move(backendUserFile(), $filename);

            $updateRecord['profile_picture'] = url(backendUserFile(),$filename);
            $oldImage = $userRecord->profile_picture;

        }
        /*if (isset($data['password']))
        {
            $updateRecord['password'] = bcrypt($data['password']);
        }*/
        $this->adminRepository->update($userRecord->id, $updateRecord);

        if (isset($oldImage)) {

            $this->safeRemoveImage($oldImage, backendUserFile());

        }

        return redirect()
            ->route('micro-hub.users.edit-profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Get Function For Password Change
     *
     */
    public function microHubChangePassword()
    {
        return view('admin.micro-hub.users.changePassword');
    }

    /**
     * Post Function For Password Change
     *
     */
    public function microHubProcessChangePassword(UpdatePasswordRequest $request)
    {
        $id = Auth::user()->id;
        if(Hash::check($request->get('oldPassword'),Auth::user()->password)){
            $data['password'] = bcrypt($request->get('password'));
            $this->adminRepository->update($id, $data);
            return redirect()
                ->route('micro-hub.users.change-password')
                ->with('success', 'Password has been changed successfully.');
        }else{
            return redirect()
                ->route('micro-hub.users.change-password')
                ->with('error', 'Please enter the old password correctly');
        }

    }
}

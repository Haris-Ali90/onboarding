<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreSubAdminRequest;
use App\Http\Requests\Admin\UpdateSubAdminRequest;
use App\Mail\WelcomeMail;
use App\Models\OrderCategory;
use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use DB;
class SubAdminController extends Controller
{

    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return backend_view('subAdmin.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        //$query = User::where('id','!=',auth()->user()->id);//->NotAdmin();


        $query =  User::where('role_id','!=', User::ROLE_ID)->whereNull('login_type')->where('id','!=',auth()->user()->id);

        return $datatables->eloquent($query)

            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->editColumn('created_at', static function ($record) {
                return $record->created_at;
            })
            ->editColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('status', static function ($record) {

                return backend_view('subAdmin.status', compact('record') );

            })
                ->addColumn('image', static function ($record) {

                    return backend_view('subAdmin.image', compact('record') );

                })
            ->addColumn('action', static function ($record) {
                return backend_view('subAdmin.action', compact('record'));
            })
            ->rawColumns(['status', 'phone','link', 'action'])
            ->make(true);
    }

    public function active(User $record)
    {
        $record->activate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub admin has been active successfully!');
    }

    public function inactive(User $record)
    {
        $record->deactivate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub admin has been inactive successfully!');
    }

    /**
     * Order Category create form open.
     *
     */
    public function create()
    {
        $role_list  = Roles::NotAdminRole()->get();
        return view('admin.subAdmin.create',compact('role_list'));
    }

    public function store(StoreSubAdminRequest $request)
    {

        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );
        $data = $request->all();

        $createRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            'user_name' => strtolower($data['first_name'] . $data['last_name']),
            'email' => $data['email'],
            'phone' => phoneFormat($data['phone']),
            'address' => $data['address'] ?? '',
            'password' => \Hash::make($data['password']),
            'role_id' =>$data['role'],
            'role_type' =>2,
            'status' => 1,
            'userType' => 'admin',

        ];
        //$file = $request->file('upload_file');
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());

            $data['upload_file'] = $fileName;
            $createRecord['profile_picture'] = url(backendUserFile() . $file->getClientOriginalName());

        }
        else{
            $createRecord['profile_picture'] = url(backendUserFile() . 'default.jpg');
        }



        $user=$this->userRepository->create($createRecord);
        $token = hash('ripemd160',uniqid(rand(),true));
        \Illuminate\Support\Facades\DB::table('onboarding_password_resets')
            ->insert(['email'=> $data['email'],'role_type' =>  2,'token' => $token]);

        $email = base64_encode ($data['email']);
        $user->sendSubAdminEmail($email,$token,2);
                return redirect()
                    ->route('sub-admin.index')
                    ->with('success', 'Sub admin added successfully.');

            }

    /**
             * Display the specified resource.
             *
             */
    public function show( $subadmin)
    {

        $id=base64_decode ($subadmin);

         $sub_admin=User ::find($id);
/*User ::find($id);*/
        $permissions= Permissions::get();
      //  return view('admin.subAdmin.show', compact('sub_admin','permissions','rights'));
        return view('admin.subAdmin.show', compact('sub_admin','permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($subadmin)
    {
        $id=base64_decode ($subadmin);

        $sub_admin=User ::find($id);

        $role_list  = Roles::NotAdminRole()->get();
        $permissions= Permissions::get();

       // return view('admin.subAdmin.edit', compact('sub_admin','permissions','rights','role_list'));
        return view('admin.subAdmin.edit', compact('sub_admin','permissions','role_list'));
    }

    public function update(UpdateSubAdminRequest $request, User $sub_admin)
    {
        $exceptFields = [
            '_token',
            '_method',
        ];

        $data = $request->all();
        $updateRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            'user_name' => strtolower($data['first_name'] . $data['last_name']),
            'email' => $data['email'],
            'phone' => phoneFormat($data['phone']),
            'address' => $data['address'] ?? '',
            'role_id' =>$data['role'],

        ];
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $updateRecord['profile_picture'] =  url(backendUserFile() .$fileName);

        }



        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $updateRecord['password'] = \Hash::make( $data['password'] );
        }

   $this->userRepository->update($sub_admin->id, $updateRecord);
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub admin updated successfully.');
    }

    /**
     * Removes the resource from database.
     */
    public function destroy(User $sub_admin)
    {
        $this->userRepository->delete($sub_admin->id);
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub admin has removed successfully!');
    }

    //Micro Hub Sub Admin Work

    public function microHubIndex()
    {
        return backend_view('micro-hub.subAdmin.index');
    }

    public function microHubData(DataTables $datatables, Request $request): JsonResponse
    {
        //$query = User::where('id','!=',auth()->user()->id);//->NotAdmin();


        $query = User::where('role_id', '!=', User::ROLE_ID)->where('login_type', 'micro_hub')->where('email','!=', 'microhubadmin@joeyco.com')->where('id', '!=', auth()->user()->id);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->editColumn('created_at', static function ($record) {
                return $record->created_at;
            })
            ->editColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('status', static function ($record) {

                return backend_view('micro-hub.subAdmin.status', compact('record'));

            })
            ->addColumn('image', static function ($record) {

                return backend_view('micro-hub.subAdmin.image', compact('record'));

            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.subAdmin.action', compact('record'));
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * Order Category create form open.
     *
     */
    public function microHubCreate()
    {
        $role_list = Roles::NotAdminRole()->MicroHubRole()->get();
        return view('admin.micro-hub.subAdmin.create', compact('role_list'));
    }

    public function microHubStore(StoreSubAdminRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );
        $data = $request->all();

        $createRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            'user_name' => strtolower($data['first_name'] . $data['last_name']),
            'email' => $data['email'],
            'phone' => phoneFormat($data['phone']),
            'address' => $data['address'] ?? '',
            'password' => \Hash::make($data['password']),
            'role_id' => $data['role'],
            'role_type' => 2,
            'status' => 1,
            'userType' => 'admin',
            'login_type' => 'micro_hub',

        ];
        //$file = $request->file('upload_file');
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());

            $data['upload_file'] = $fileName;
            $createRecord['profile_picture'] = url(backendUserFile() . $file->getClientOriginalName());

        } else {
            $createRecord['profile_picture'] = url(backendUserFile() . 'default.png');
        }

        $user = $this->userRepository->create($createRecord);

        /* $token = hash('ripemd160', uniqid(rand(), true));
         \Illuminate\Support\Facades\DB::table('onboarding_password_resets')
             ->insert(['email' => $data['email'], 'role_type' => 2, 'token' => $token]);

         $email = base64_encode($data['email']);
         $user->sendSubAdminEmail($email, $token, 2);*/
        return redirect()
            ->route('micro-hub.sub-admin.index')
            ->with('success', 'Sub admin added successfully.');

    }

    public function microHubActive(User $record)
    {
        $record->activate();
        return redirect()
            ->route('micro-hub.sub-admin.index')
            ->with('success', 'Sub admin has been active successfully!');
    }

    public function microHubInactive(User $record)
    {
        $record->deactivate();
        return redirect()
            ->route('micro-hub.sub-admin.index')
            ->with('success', 'Sub admin has been inactive successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function microHubEdit($subadmin)
    {
        $id = base64_decode($subadmin);

        $sub_admin = User::find($id);

        $role_list = Roles::NotAdminRole()->MicroHubRole()->get();
        //$permissions = Permissions::get();

        // return view('admin.subAdmin.edit', compact('sub_admin','permissions','rights','role_list'));
        return view('admin.micro-hub.subAdmin.edit', compact('sub_admin','role_list'));
    }

    public function microHubUpdate(UpdateSubAdminRequest $request, User $sub_admin)
    {
        $exceptFields = [
            '_token',
            '_method',
        ];

        $data = $request->all();
        $updateRecord = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            'user_name' => strtolower($data['first_name'] . $data['last_name']),
            'email' => $data['email'],
            'phone' => phoneFormat($data['phone']),
            'address' => $data['address'] ?? '',
            'role_id' => $data['role'],

        ];
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $updateRecord['profile_picture'] = url(backendUserFile() . $fileName);

        }


        if ($request->has('password') && $request->get('password', '') != '') {
            $updateRecord['password'] = \Hash::make($data['password']);
        }

        $this->userRepository->update($sub_admin->id, $updateRecord);
        return redirect()
            ->route('micro-hub.sub-admin.index')
            ->with('success', 'Sub admin updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     */
    public function microHubShow($subadmin)
    {
        $id = base64_decode($subadmin);

        $sub_admin = User::find($id);
        /*User ::find($id);*/
        $permissions = Permissions::get();
        //  return view('admin.subAdmin.show', compact('sub_admin','permissions','rights'));
        return view('admin.micro-hub.subAdmin.show', compact('sub_admin', 'permissions'));
    }

    /**
     * Removes the resource from database.
     */
    public function microHubDestroy(User $sub_admin)
    {
        $this->userRepository->delete($sub_admin->id);
        return redirect()
            ->route('micro-hub.sub-admin.index')
            ->with('success', 'Sub-admin has been removed successfully!');
    }

}

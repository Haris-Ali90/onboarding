<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;
use Session;
use Validator;
use App\Models\Roles;
use App\Models\Permissions;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;


class RoleController extends Controller
{

    private $RoleRepository;

    public function __construct(RoleRepositoryInterface $RoleRepositoryInterface)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->RoleRepository = $RoleRepositoryInterface;
    }

    /**
     * Index action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $Roles =  Roles::NotAdminRole()->where('type','onboarding')->get();

        return backend_view('role.index',compact(
            'Roles'
        ));
    }

    /**
     * Create action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();

        return backend_view('role.create',compact(
            'dashboard_card_permissions'
        ));
    }

    /**
     * store action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(RoleRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        if(isset($data['dashboard_card_permission']))
        {
            $dashboard_cards_rights = implode(",",$data['dashboard_card_permission']);
        }
        /*createing inserting data*/
        $create = [
            'display_name' => $data['name'],
            'role_name' => SlugMaker($data['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type'=> Roles::ROLE_TYPE_NAME,
        ];

        /*inserting data*/
        $this->RoleRepository->create($create);

        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role added successfully.');


    }

    /**
     * show action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Roles $role)
    {
        $permissions =  Permissions::GetAllPermissions();
        $route_names = $role->Permissions->pluck('route_name')->toArray();
        //dd($permissions,$role);
        return backend_view('role.show',compact(
            'role',
            'route_names',
            'permissions'
        ));
    }

    /**
     * edit action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Roles $role)
    {

        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();

        return backend_view('role.edit',compact(
            'role',
            'dashboard_card_permissions'
        ));
    }

    /**
     * update action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateRoleRequest $request,$role)
    {
        /*getting all requests data*/
        $Postdata = $request->all();

        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        if(isset($Postdata['dashboard_card_permission']))
        {
            $dashboard_cards_rights = implode(",",$Postdata['dashboard_card_permission']);
        }

        /*creating updating data*/
        $update_data = [
            'display_name' => $Postdata['name'],
            'role_name' => SlugMaker($Postdata['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type'=> Roles::ROLE_TYPE_NAME,
        ];


        /*ipdating data*/
        $this->RoleRepository->update($role,$update_data);

        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role updated successfully.');

    }

    public function setPermissions(Roles $role)
    {
        // getting permissions
        $permissions_list = Permissions::getAllPermissions();

        //dd($permissions_list);
        return backend_view('role.set-permissions',compact(
            'role',
            'permissions_list'
        ));
    }

    public function setPermissionsUpdate(Request $request,$role)
    {
        // now creating insert data of permissions
        $insert_permissions = [];

        $role_permissions = $request->permissions ?? [];
        //$role_permissions = $request->permissions;

        foreach($role_permissions as $role_permission)
        {
            if(strpos($role_permission, '|') !== false)
            {
                foreach(explode('|',$role_permission) as $child_permission )
                {
                    $insert_permissions[] =['route_name'=> $child_permission, 'role_id'=>$role];
                }
            }
            else
            {
                $insert_permissions[] = ['route_name'=> $role_permission, 'role_id'=>$role];
            }

        }

        // deleting old data
        $delete = Permissions::where('role_id',$role)->update(['is_delete' => 1]);

        //inserting new data
        $crate_permissions = Permissions::insert($insert_permissions);

        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role permissions updated successfully');

    }

    //Micro Hub Role Code

    /**
     * Micro HubIndex action
     *
     */
    public function microHubIndex()
    {
        $Roles =  Roles::NotAdminRole()->where('type','micro_hub_onboarding')->get();

        return backend_view('micro-hub.role.index',compact(
            'Roles'
        ));
    }

    /**
     * Micro Hub Create action
     *
     */
    public function microHubCreate()
    {
        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();

        return backend_view('micro-hub.role.create',compact(
            'dashboard_card_permissions'
        ));
    }

    /**
     * store action
     *
     */
    public function microHubStore(RoleRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        /*if(isset($data['dashboard_card_permission']))
        {
            $dashboard_cards_rights = implode(",",$data['dashboard_card_permission']);
        }*/
        /*createing inserting data*/
        $create = [
            'display_name' => $data['name'],
            'role_name' => SlugMaker($data['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type'=> Roles::MICRO_HUB_ROLE_TYPE_NAME,
        ];

        /*inserting data*/
        $this->RoleRepository->create($create);

        /*return data */
        return redirect()
            ->route('micro-hub.role.index')
            ->with('success', 'Role added successfully.');


    }

    /**
     * edit action
     *
     */
    public function microHubEdit(Roles $role)
    {

        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();

        return backend_view('micro-hub.role.edit',compact(
            'role',
            'dashboard_card_permissions'
        ));
    }

    /**
     * update action
     *
     */
    public function microHubUpdate(UpdateRoleRequest $request,$role)
    {
        /*getting all requests data*/
        $Postdata = $request->all();

        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        /*if(isset($Postdata['dashboard_card_permission']))
        {
            $dashboard_cards_rights = implode(",",$Postdata['dashboard_card_permission']);
        }*/

        /*creating updating data*/
        $update_data = [
            'display_name' => $Postdata['name'],
            'role_name' => SlugMaker($Postdata['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type'=> Roles::MICRO_HUB_ROLE_TYPE_NAME,
        ];


        /*ipdating data*/
        $this->RoleRepository->update($role,$update_data);

        /*return data */
        return redirect()
            ->route('micro-hub.role.index')
            ->with('success', 'Role updated successfully.');

    }

    /**
     * show action
     *
     */
    public function microHubShow(Roles $role)
    {
        $permissions =  Permissions::getAllMicroHubPermissions();
        $route_names = $role->Permissions->pluck('route_name')->toArray();
        //dd($permissions,$role);
        return backend_view('micro-hub.role.show',compact(
            'role',
            'route_names',
            'permissions'
        ));
    }

    /**
     * Set Permission
     *
     */
    public function setMicroHubPermissions(Roles $role)
    {
        // getting permissions
        $permissions_list = Permissions::getAllMicroHubPermissions();

        return backend_view('micro-hub.role.set-permissions',compact(
            'role',
            'permissions_list'
        ));
    }

    public function setMicroHubPermissionsUpdate(Request $request,$role)
    {
        // now creating insert data of permissions
        $insert_permissions = [];

        $role_permissions = $request->permissions ?? [];
        //$role_permissions = $request->permissions;

        foreach($role_permissions as $role_permission)
        {
            if(strpos($role_permission, '|') !== false)
            {
                foreach(explode('|',$role_permission) as $child_permission )
                {
                    $insert_permissions[] =['route_name'=> $child_permission, 'role_id'=>$role];
                }
            }
            else
            {
                $insert_permissions[] = ['route_name'=> $role_permission, 'role_id'=>$role];
            }

        }

        // deleting old data
        $delete = Permissions::where('role_id',$role)->update(['is_delete' => 1]);

        //inserting new data
        $crate_permissions = Permissions::insert($insert_permissions);

        /*return data */
        return redirect()
            ->route('micro-hub.role.index')
            ->with('success', 'Role permissions updated successfully');

    }


}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreFlagCategoryRequest;
use App\Http\Requests\Admin\StoreFlagIncidentRequest;
use App\Http\Requests\Admin\UpdateFlagCategoryRequest;
use App\Http\Requests\Admin\UpdateFlagIncidentRequest;
use App\Models\CustomerFlagCategories;
use App\Models\CustomerIncidents;
use App\Models\Vendor;
use App\Models\FlagOrderType;
use App\Repositories\Interfaces\CustomerFlagCategoryRepositoryInterface;
use App\Repositories\Interfaces\CustomerFlagCategoryValuesRepositoryInterface;
use App\Repositories\Interfaces\CustomerIncidentRepositoryInterface;
use App\Repositories\Interfaces\FlagCategoryMetaDataRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomerServiceController extends Controller
{

    private $customerFlagCategoryRepository;
    private $FlagCategoryMetaDataRepository;
    private $customerFlagCategoryValuesRepository;
    private $customerIncidentRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */

    public function __construct(
        CustomerFlagCategoryRepositoryInterface $customerFlagCategoryRepository,
        FlagCategoryMetaDataRepositoryInterface $FlagCategoryMetaDataRepository,
        CustomerFlagCategoryValuesRepositoryInterface $customerFlagCategoryValuesRepository,
        CustomerIncidentRepositoryInterface $customerIncidentRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->customerFlagCategoryRepository = $customerFlagCategoryRepository;
        $this->FlagCategoryMetaDataRepository = $FlagCategoryMetaDataRepository;
        $this->customerFlagCategoryValuesRepository = $customerFlagCategoryValuesRepository;
        $this->customerIncidentRepository = $customerIncidentRepository;
    }

    /**
     * Function of View Flags.
     *
     */
    public function index()
    {
        $vendors = Vendor::get();
        $flagCategory = CustomerFlagCategories::IsParent()->get();

        return view('admin.customer-services.index', compact('flagCategory','vendors'));
    }

    /**
     * Function of enable.
     *
     */
    public function isEnable(CustomerFlagCategories $record)
    {
        $record->enable();
        $category_name = $record->category_name;
        return redirect()
            //->route('customer-service.index')
            ->back()
            ->with('success', $category_name.' enable successfully!');
    }

    /**
     * Function of disable.
     *
     */
    public function isDisable(CustomerFlagCategories $record)
    {
        $record->disable();
        $category_name = $record->category_name;
        return redirect()
            //->route('customer-service.index')
            ->back()
            ->with('success', $category_name.' disable successfully!');
    }

    /**
     * Function of create view.
     *
     */
    public function create()
    {
        // getting incident values
        $incident_list = CustomerIncidents::EnableIncident()->get();
        // getting vendors
        $vendor_list = Vendor::Enabled()->get();
        //dd($vendor_list);
        // getting order types
        $flag_order_types = FlagOrderType::all();
        return view('admin.customer-services.create',
            compact(
                'incident_list',
                'vendor_list',
                'flag_order_types'
            ));
    }

    /**
     * Function to create category.
     *
     */
    public function store(StoreFlagCategoryRequest $request)
    {
        // getting all request data
        $data = $request->all();

        /*saving data*/
        DB::beginTransaction();
        try {

            // inserting main category
            $category = $this->customerFlagCategoryRepository->create([
                'category_name' => $data['category_name'],
                'have_childs' => count($data['child_uid']),
                'uid' => $data['main_cat_uid'],
            ]);

            $second_child_save_data = [];
            $second_child_count = array_count_values($data['parent_cat']);
            // creating second child
            foreach($data['child_uid'] as $key => $child_uid )
            {
                $current_second_cat_child_count = (isset($second_child_count[$child_uid])) ? $second_child_count[$child_uid] : 0 ;
                $save_data = $this->customerFlagCategoryRepository->create([
                    'category_name' => $data['child_category_name'][$key],
                    'have_childs' => $current_second_cat_child_count,
                    'parent_id' => $category->id,
                    'uid' => $child_uid,
                ]);

                $second_child_save_data[$child_uid] = $save_data->toArray();
            }


            // creating main category meta data
            $flagMetaData = [];

            // creating portals data
            if(isset($data['portal_type']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('portal')] = $data['portal_type'];
            }

            // creating order type meta data
            if(isset($data['order_type']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('order_type')] = $data['order_type'];
            }

            // creating vendor meta data
            if(isset($data['vendors']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('vendor_relation')] = $data['vendors'];
            }

            // checking the flag can show on route
            $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('is_show_on_route')] = [$data['is_show_route']];

            // saving metadata
            $SavedflagMetaData =  $this->FlagCategoryMetaDataRepository->MultiDataSave($category->id,$flagMetaData);

            // now creating grand child category data
            foreach ($data['sub_category_name'] as $index => $sub_category_name) {

                $getting_parent_cat_id = $second_child_save_data[$data['parent_cat'][$index]]['id'];

                // creating grand child category
                $child_cat_data = $this->customerFlagCategoryRepository->create([
                    'category_name' => $sub_category_name,
                    'uid' => $data['grand_child_uid'][$index],
                    'parent_id' => $getting_parent_cat_id

                ]);

                // creating child category values
                $child_cat_val = $this->customerFlagCategoryValuesRepository->create([
                    'category_ref_id' => $child_cat_data->id,
                    'incident_1_ref_id' => $data['incident_1_ref_id'][$index],
                    'incident_2_ref_id' => $data['incident_2_ref_id'][$index],
                    'incident_3_ref_id' => $data['incident_3_ref_id'][$index],
                    'conclusion_ref_id' => $data['conclusion_ref_id'][$index],
                    'finance_incident_1' => $data['finance_incident_1'][$index],
                    'finance_incident_1_operator' => $data['finance_incident_1_operator'][$index],
                    'finance_incident_2' => $data['finance_incident_2'][$index],
                    'finance_incident_2_operator' => $data['finance_incident_2_operator'][$index],
                    'finance_incident_3' => $data['finance_incident_3'][$index],
                    'finance_incident_3_operator' => $data['finance_incident_3_operator'][$index],
                    'finance_conclusion' => $data['finance_conclusion'][$index],
                    'finance_conclusion_operator' => $data['finance_conclusion_operator'][$index],
                    'rating_1' => $data['rating_1'][$index],
                    'rating_1_operator' => $data['rating_1_operator'][$index],
                    'rating_2' => $data['rating_2'][$index],
                    'rating_2_operator' => $data['rating_2_operator'][$index],
                    'rating_3' => $data['rating_3'][$index],
                    'rating_3_operator' => $data['rating_3_operator'][$index],
                    'rating_4' => $data['rating_4'][$index],
                    'rating_4_operator' => $data['rating_4_operator'][$index],
					'refresh_rate_incident_1' => $data['refresh_rate_incident_1'][$index],
                    'is_applied_refresh_rate_1' => ($data['refresh_rate_incident_1'][$index] > 0) ? 1 : 0 ,
                    'refresh_rate_incident_2' => $data['refresh_rate_incident_2'][$index],
                    'is_applied_refresh_rate_2' => ($data['refresh_rate_incident_2'][$index] > 0) ? 1 : 0,
                    'refresh_rate_incident_3' => $data['refresh_rate_incident_3'][$index],
                    'is_applied_refresh_rate_3' => ($data['refresh_rate_incident_3'][$index] > 0) ? 1 : 0,
                    'refresh_rate_conclusion' => $data['refresh_rate_conclusion'][$index],
                    'is_applied_refresh_rate_conclusion' => ($data['refresh_rate_conclusion'][$index] > 0) ? 1 : 0,
                ]);
            }

            DB::commit();
        } catch (\Exception $e)
        {
            DB::rollback();
            Session::put('error', 'Sorry something went wrong. please try again later !');
            //return redirect()->route('customer-service.edit',$plan_id);
            return redirect()->route('customer-service.create');
        }
        return redirect()
            ->route('customer-service.index')
            ->with('success', 'Flag category added successfully.');

    }

    /**
     * Function of edit view.
     *
     */
    public function edit(CustomerFlagCategories $customer_service)
    {
        //getting mata data

        $FlagMataData = $customer_service->FlagMataData;
        // getting portal data
        $selected_portal_types = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('portal'))->pluck('value')->toArray();
        $selected_portal_types = implode(',',$selected_portal_types);

        // getting selected order type
        $selected_order_type = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('order_type'))->pluck('value')->toArray();
        $selected_order_type = implode(',',$selected_order_type);

        // getting selected vendors type
        $selected_vendors = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('vendor_relation'))->pluck('value')->toArray();
        $selected_vendors = implode(',',$selected_vendors);

        // selected is show on route
        $selected_is_show_on_route = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('is_show_on_route'))->pluck('value')->toArray();
        $selected_is_show_on_route = implode(',',$selected_is_show_on_route);

        // getting vendors
        $vendor_list = Vendor::Enabled()->get();
        // getting order types
        $flag_order_types = FlagOrderType::all();


        //getting incident
        $incident_list = CustomerIncidents::EnableIncident()->pluck('name', 'id')->toArray();

        return view('admin.customer-services.edit', compact(
            'customer_service',
            'vendor_list',
            'incident_list',
            'flag_order_types',
            'selected_portal_types',
            'selected_order_type',
            'selected_vendors',
            'selected_is_show_on_route'
        ));
    }

    /**
     * Function to update flag.
     *
     */
    public function update($category_id, UpdateFlagCategoryRequest $request)
    {

        DB::beginTransaction();
        try {

            $category = $request->all();

            // Update main category
            $categories_id = $this->customerFlagCategoryRepository->update($category_id, [
                'category_name' => $category['category_name'],
                'have_childs' => count($category['child_uid']),
                'uid' => $category['main_cat_uid'],
            ]);

            $second_child_save_data = [];
            $second_child_count = array_count_values($category['parent_cat']);
            // creating second child
            foreach($category['child_uid'] as $key => $child_uid )
            {

                $current_second_cat_child_count = (isset($second_child_count[$child_uid])) ? $second_child_count[$child_uid] : 0 ;

                $save_data = $this->customerFlagCategoryRepository->updateOrCreate($category['second_child_id'][$key],[
                    'category_name' => $category['child_category_name'][$key],
                    'have_childs' => $current_second_cat_child_count,
                    'parent_id' => $category_id,
                    'uid' => $child_uid,
                ]);

                $second_child_save_data[$child_uid] = $save_data->toArray();

            }


            // creating main category meta data
            $flagMetaData = [];

            // creating portals data
            if(isset($category['portal_type']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('portal')] = $category['portal_type'];
            }

            // creating order type meta data
            if(isset($category['order_type']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('order_type')] = $category['order_type'];
            }

            // creating vendor meta data
            if(isset($category['vendors']))
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('vendor_relation')] = $category['vendors'];

            }
            else // making selection empty
            {
                $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('vendor_relation')] = [];
            }

            // checking the flag can show on route
            $flagMetaData[$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('is_show_on_route')] = [$category['is_show_route']];

            // saving metadata
            $UpdatedflagMetaData =  $this->FlagCategoryMetaDataRepository->MultiDataSync($category_id,$flagMetaData);


            // now update or create child category data
            foreach ($category['sub_category_name'] as $index => $sub_category_name) {


                $getting_parent_cat_id = $second_child_save_data[$category['parent_cat'][$index]]['id'];

                $child_id = (isset($category['child_ids'][$index])) ? $category['child_ids'][$index] : 0;
                // creating child category
                $child_cat_data = $this->customerFlagCategoryRepository->updateOrCreate($child_id, [
                    'category_name' => $sub_category_name,
                    'uid' => $category['grand_child_uid'][$index],
                    'parent_id' => $getting_parent_cat_id,
                ]);


                // creating child category values
                $category['child_ids'][$index] = $this->customerFlagCategoryValuesRepository->updateOrCreateByCategory($child_cat_data->id, [

                    'category_ref_id' => $child_cat_data->id,
                    'incident_1_ref_id' => $category['incident_1_ref_id'][$index] ?? 0,
                    'incident_2_ref_id' => $category['incident_2_ref_id'][$index] ?? 0,
                    'incident_3_ref_id' => $category['incident_3_ref_id'][$index] ?? 0,
                    'conclusion_ref_id' => $category['conclusion_ref_id'][$index] ?? 0,
                    'finance_incident_1' => $category['finance_incident_1'][$index],
                    'finance_incident_1_operator' => $category['finance_incident_1_operator'][$index] ?? 0,
                    'finance_incident_2' => $category['finance_incident_2'][$index] ?? 0,
                    'finance_incident_2_operator' => $category['finance_incident_2_operator'][$index] ?? 0,
                    'finance_incident_3' => $category['finance_incident_3'][$index] ?? 0,
                    'finance_incident_3_operator' => $category['finance_incident_3_operator'][$index] ?? 0,
                    'finance_conclusion' => $category['finance_conclusion'][$index] ?? 0,
                    'finance_conclusion_operator' => $category['finance_conclusion_operator'][$index] ?? 0,
                    'rating_1' => $category['rating_1'][$index] ?? 0,
                    'rating_1_operator' => $category['rating_1_operator'][$index] ?? 0,
                    'rating_2' => $category['rating_2'][$index] ?? 0,
                    'rating_2_operator' => $category['rating_2_operator'][$index] ?? 0,
                    'rating_3' => $category['rating_3'][$index] ?? 0,
                    'rating_3_operator' => $category['rating_3_operator'][$index] ?? 0,
                    'rating_4' => $category['rating_4'][$index] ?? 0,
                    'rating_4_operator' => $category['rating_4_operator'][$index] ?? 0,
					'refresh_rate_incident_1' => $category['refresh_rate_incident_1'][$index] ?? 0,
                    'is_applied_refresh_rate_1' => ($category['refresh_rate_incident_1'][$index] > 0) ? 1 : 0 ,
                    'refresh_rate_incident_2' => $category['refresh_rate_incident_2'][$index] ?? 0,
                    'is_applied_refresh_rate_2' => ($category['refresh_rate_incident_2'][$index] > 0) ? 1 : 0,
                    'refresh_rate_incident_3' => $category['refresh_rate_incident_3'][$index] ?? 0,
                    'is_applied_refresh_rate_3' => ($category['refresh_rate_incident_3'][$index] > 0) ? 1 : 0,
                    'refresh_rate_conclusion' => $category['refresh_rate_conclusion'][$index] ?? 0,
                    'is_applied_refresh_rate_conclusion' => ($category['refresh_rate_conclusion'][$index] > 0) ? 1 : 0,
                ]);


            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');
            //return redirect()->route('customer-service.edit',$plan_id);
            return redirect()->route('customer-service.edit',$category_id);
        }
        return redirect()
            ->route('customer-service.index')
            ->with('success', 'Flag category and parent category updated successfully.');


    }

    /**
     * Function of detail view.
     *
     */
    public function show(CustomerFlagCategories $customer_service)
    {

        //getting mata data
        $FlagMataData = $customer_service->FlagMataData;
        // getting portal data
        $selected_portal_types = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('portal'))->pluck('value')->toArray();
        $selected_portal_types = implode(', ',$selected_portal_types);

        // getting selected order type
        $selected_order_type = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('order_type'))->pluck('value')->toArray();
        $selected_order_type = implode(', ',$selected_order_type);

        // getting selected vendors type
        $selected_vendors = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('vendor_relation'))->pluck('value')->toArray();
        $vendors_data = Vendor::whereIn('id',$selected_vendors)->get();

        // selected is show on route
        $selected_is_show_on_route = $FlagMataData->where('type',$this->FlagCategoryMetaDataRepository->getFlagMetaTypes('is_show_on_route'))->pluck('value')->toArray();
        $selected_is_show_on_route = implode(',',$selected_is_show_on_route);

//dd([$selected_vendors,$vendors_data]);
        //getting incident
        $incident_list = CustomerIncidents::pluck('name', 'id')->toArray();
//dd($incident_list);
        return view('admin.customer-services.detail', compact(
            'customer_service',
            'incident_list',
            'selected_portal_types',
            'selected_order_type',
            'vendors_data',
            'selected_is_show_on_route'
        ));
    }

    /**
     * Function to delete sub category.
     *
     */
    public function deleteSubCategoryData(Request $request)
    {
//        $delete_sub_category = $this->customerFlagCategoryRepository->deleteSubCategory($request->id);
//
//        $delete_sub_category_value = $this->customerFlagCategoryValuesRepository->deleteCategoryValue($request->id);

        return response()->json(['status' => true, 'message' => 'Sub category remove successfully.']);

    }

    /**
     * Function of View Flags Incident Value.
     *
     */
    public function flagIncidentIndex()
    {
        $flagIncidentValue = CustomerIncidents::where('system_default_incident','0')->get();

        return view('admin.customer-services.flag-incident.index', compact('flagIncidentValue'));
    }

    /**
     * Function of enable Flag Incident.
     *
     */
    public function flagIncidentEnable(CustomerIncidents $record)
    {
        $record->enable();
        return redirect()
            //->route('customer-service.index')
            ->back()
            ->with('success', 'Flag incident has been enable successfully!');
    }

    /**
     * Function of disable Flag Incident.
     *
     */
    public function flagIncidentDisable(CustomerIncidents $record)
    {
        $record->disable();
        return redirect()
            //->route('customer-service.index')
            ->back()
            ->with('success','Flag incident has been disable successfully!');
    }

    /**
     * Function of Flag Incident create view.
     *
     */
    public function flagIncidentCreate()
    {
        return view('admin.customer-services.flag-incident.create');
    }

    /**
     * Function to Flag Incident create category.
     *
     */
    public function flagIncidentStore(StoreFlagIncidentRequest $request)
    {
        // getting all request data
        $data = $request->all();
        $labelFlagIncident = str_replace(' ', '_', $data['name']);
        /*saving data*/
        DB::beginTransaction();
        try {
            $createFlagIncident = [
                'name' => $data['name'],
                'label' => SlugMaker($data['name']),
                'priority'=> 1,
                'days_duration' => $data['days_duration'],
                'system_default_incident' => 0,
                'is_applied_login_validation' => 1,

            ];

            $this->customerIncidentRepository->create($createFlagIncident);
            DB::commit();
        } catch (\Exception $e)
        {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');
            //return redirect()->route('customer-service.edit',$plan_id);
            return redirect()->route('flag-incident.create');
        }
        return redirect()
            ->route('flag-incident.index')
            ->with('success', 'Flag incident added successfully.');

    }

    /**
     * Function of Flag Incident edit view.
     *
     */
    public function flagIncidentEdit($flag_incident)
    {
        //getting incident
        $incident_list = CustomerIncidents::find($flag_incident);
        return view('admin.customer-services.flag-incident.edit', compact(
            'incident_list',
            'flag_incident'
        ));
    }

    /**
     * Function to Update Flag Incident .
     *
     */
    public function flagIncidentUpdate($incident_id, UpdateFlagIncidentRequest $request)
    {

        DB::beginTransaction();
        try {
            $incidentValue = $request->all();

            // Update main category
            $this->customerIncidentRepository->update($incident_id, [
                'name' => $incidentValue['name'],
                'label' => SlugMaker($incidentValue['name']),
                'days_duration' => $incidentValue['days_duration'],
            ]);



            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::put('error', 'Sorry something went wrong. please try again later !');
            //return redirect()->route('customer-service.edit',$plan_id);
            return redirect()->route('flag-incident.edit',$category_id);
        }
        return redirect()
            ->route('flag-incident.index')
            ->with('success', 'Flag incident updated successfully.');
        //dd([$category_id, $portal_data]);


    }

}

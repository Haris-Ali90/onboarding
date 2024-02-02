<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreVendorsCountRequest;
use App\Http\Requests\Admin\UpdateVendorsCountRequest;
use App\Models\Vendor;
use App\Models\Vendors;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use App\Repositories\Interfaces\VendorsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VendorsController extends Controller
{
    private $vendorsRepository;
    private $vendorRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(VendorsRepositoryInterface $vendorsRepository,VendorRepositoryInterface $vendorRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->vendorsRepository = $vendorsRepository;
        $this->vendorRepository =$vendorRepository;

    }


    public function index()
    {
        return view('admin.vendors.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Vendors::query();


        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('vendor', static function ($record) {

                    return $record->name;

            })
            ->addColumn('order_count', static function ($record) {

                    return $record->order_count;


            })
            ->addColumn('score', static function ($record) {
                    return $record->score;

            })
            ->addColumn('action', static function ($record) {
                return backend_view('vendors.action', compact('record'));
            })
            ->rawColumns(['vendor', 'is_active'])
            ->make(true);
    }

    public function create()
    {
        $data['vendor'] = Vendor::all();
        return view('admin.vendors.create', $data);
    }


    public function store(StoreVendorsCountRequest $request)
    {
        $data = $request->all();

       $name= Vendor::where('id',$data['vendor_id'])->first();

        $updateRecord = [
            'vendor_id' => $data['vendor_id'],
            'order_count' => $data['count'],
            'score' => $data['score'],
            'type'=> $data['type']
        ];

        $this->vendorsRepository->create($record);
        return view('admin.vendors.index');
    }

    public function edit(Vendors $vendor)
    {

      $vendors= Vendors::all();

      //  $VendorData=Vendor::where('id',$vendor->vendor_id)->first();

        return view('admin.vendors.edit', compact('vendor','vendors'));
    }

    public function update(UpdateVendorsCountRequest $updateVendorsCountRequest, Vendors $vendor){

        $data = $updateVendorsCountRequest->all();


        if($data['type']=='basic'){
            $updateRecord= [
                'score' => $data['score'],
                'type'=>$data['type'],
                'order_count' => 0
            ];
        }else {
            $updateRecord = [
                'score' => $data['score'],
                'type' => $data['type'],
                'order_count' => $data['count']
            ];
        }

        $test=Vendor::where('id',$data['vendors_id'])->update($updateRecord);

        return view('admin.vendors.index');
    }

    public function destroy(Vendors $vendor)
    {
        $data = $vendor->delete();
        return view('admin.vendors.index');
    }


}

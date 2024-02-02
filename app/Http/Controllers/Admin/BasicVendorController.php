<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreBasicVendorRequest;
use App\Models\BasicVendor;
use App\Models\Vendor;
use App\Models\Vendors;
use App\Repositories\Interfaces\BasicVendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BasicVendorController extends Controller
{
    private $basicVendorRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(BasicVendorRepositoryInterface $basicVendorRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->basicVendorRepository = $basicVendorRepository;
    }


    public function index()
    {

        return view('admin.basicVendor.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = BasicVendor::with('vendor')->select('basic_vendor.*');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('vendor', static function ($record) {
                if ($record->vendor) {
                    return $record->vendor->name;
                }
                return $record = '';
            })
            ->addColumn('action', static function ($record) {
                return backend_view('basicVendor.action', compact('record'));
            })
            ->rawColumns(['vendor,is_active'])
            ->make(true);
    }


    public function create()
    {
        $basisVendorCategoryId=BasicVendor::pluck('vendor_id');

        $data['vendor'] = Vendor::whereNotIn('id',$basisVendorCategoryId)->get();
        return view('admin.basicVendor.create', $data);
    }


    public function store(StoreBasicVendorRequest $storeBasicVendorRequest)
    {

        $data = $storeBasicVendorRequest->all();

        $Record = [
            'vendor_id' => $data['vendor_id'],
        ];

        $this->basicVendorRepository->create($Record);
        return redirect()
            ->route('basic-vendor.index')
            ->with('success', 'Basic Vendor added successfully!');
    }


    public function destroy(BasicVendor $basicVendor)
    {

        $data = $basicVendor->delete();
        return redirect()
            ->route('basic-vendor.index')
            ->with('success', 'Basic vendor removed successfully!');
    }

}

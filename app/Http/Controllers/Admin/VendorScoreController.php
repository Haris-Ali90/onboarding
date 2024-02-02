<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreVendorScoreRequest;
use App\Http\Requests\Admin\UpdateVendorScoreRequest;
use App\Models\Vendor;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VendorScoreController extends Controller
{
    private $vendorRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->vendorRepository = $vendorRepository;
    }


    public function index()
    {
        return view('admin.vendorScore.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Vendor::whereNotNull('score');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('vendorScore.action', compact('record'));
            })
            ->rawColumns(['category', 'is_active'])
            ->make(true);
    }

    public function create()
    {
        $data['vendor'] = Vendor::whereNull('score')->get();
        return view('admin.vendorScore.create', $data);
    }


    public function store(StoreVendorScoreRequest $storeVendorScoreRequest,Vendor $vendor)
    {
        $data = $storeVendorScoreRequest->all();

        $record = [
            'id'=>$data['vendor_id'],
            'score' => $data['score'],
        ];

        $this->vendorRepository->update($data['vendor_id'],$record);
        return redirect()
            ->route('vendor-score.index')
            ->with('success', 'Vendor score updated successfully!');
    }

    public function edit(Vendor $vendorScore)
    {
        $data['vendor'] = Vendor::all();

        return view('admin.vendorScore.edit', compact('vendorScore'), $data);
    }

    public function update(UpdateVendorScoreRequest $updateVendorScoreRequest, Vendor $vendor){
                $data = $updateVendorScoreRequest->all();

        $record = [
            'id'=>$data['vendors_id'],
            'score' => $data['score'],
        ];


        $this->vendorRepository->update($data['vendors_id'],$record);
        return redirect()
            ->route('vendor-score.index')
            ->with('success', 'Vendor score updated successfully!');
    }

    public function destroy(Request $request)
    {
        $data = $request->all();

        $record = [
            'id'=>$data['id'],
            'score' =>null,
        ];


        $this->vendorRepository->update($data['id'],$record);
        return redirect()
            ->route('vendor-score.index')
            ->with('success', 'Vendor score was removed successfully!');
    }

}

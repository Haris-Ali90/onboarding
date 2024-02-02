<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreWorkTypeRequest;
use App\Http\Requests\Admin\UpdateWorkTypeRequest;
use App\Models\WorkType;
use App\Repositories\Interfaces\WorkTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkTypeController extends Controller
{
    private $workTypeRepository;

    /**
     * Create a new controller instance.
     *
     * @param WorkTypeRepositoryInterface $workTypeRepository
     */


    public function __construct(WorkTypeRepositoryInterface $workTypeRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->workTypeRepository = $workTypeRepository;
    }


    public function index()
    {
        return view('admin.workType.index');
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = WorkType::query();

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })

            ->addColumn('action', static function ($record) {
                return backend_view('workType.action', compact('record') );
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }


    public function create()
    {
        return view('admin.workType.create');
    }


    public function store(StoreWorkTypeRequest $request){
        $data=$request->all();

        $workTypeRecord = [
            'type' => $data['workType'],
        ];
        $this->workTypeRepository->create($workTypeRecord);
        return redirect()
            ->route('work-type.index')
            ->with('success', 'Work type added successfully!');
    }


    public function edit(WorkType $workType){
        return view('admin.workType.edit',compact('workType'));
    }


    public function update (UpdateWorkTypeRequest $updateWorkTypeRequest,WorkType $workType){

        $workTypeData = $updateWorkTypeRequest->all();

        $updateWorkTypeRecord = [
            'type' => $workTypeData['workType'],
        ];

        $this->workTypeRepository->update($workType->id, $updateWorkTypeRecord);
        return redirect()
            ->route('work-type.index')
            ->with('success', 'Work type updated successfully!');
    }


    public function destroy(WorkType $workType)
    {

        $data = $workType->delete();
        return redirect()
            ->route('work-type.index')
            ->with('success', 'Work type has removed successfully!');
    }

}

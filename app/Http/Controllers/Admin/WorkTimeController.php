<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreWorkTimeRequest;
use App\Http\Requests\Admin\UpdateWorkTimeRequest;
use App\Models\WorkTime;
use App\Repositories\Interfaces\WorkTimeRepositoryInterface;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkTimeController extends Controller
{
    private $workTimeRepository;

    /**
     * Create a new controller instance.
     *
     * @param WorkTimeRepositoryInterface $workTimeRepository
     */


    public function __construct(WorkTimeRepositoryInterface $workTimeRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->workTimeRepository = $workTimeRepository;
    }


    public function index()
    {

        return view('admin.workTime.index');
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = WorkTime::query();

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('start_time', static function ($record) {
                return date("g:i A", strtotime($record->start_time));
            })
            ->editColumn('end_time', static function ($record) {
                return date("g:i A", strtotime($record->end_time));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('workTime.action', compact('record') );
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }


    public function create()
    {
        return view('admin.workTime.create');
    }


    public function store(StoreWorkTimeRequest $request){
        $data=$request->all();
        $workTimeRecord = [
            'start_time' => $data['startTime'],
            'end_time' => $data['endTime'],
        ];

        $this->workTimeRepository->create($workTimeRecord);
        return redirect()
            ->route('work-time.index')
            ->with('success', 'Work time added successfully.');
    }



    public function edit(WorkTime $workTime){
        return view('admin.workTime.edit',compact('workTime'));
    }

    public function update (UpdateWorkTimeRequest $updateWorkTimeRequest,WorkTime $workTime){

        $workTimeData = $updateWorkTimeRequest->all();

        $updateWorkTimeRecord = [
            'start_time' => $workTimeData['start_time'],
            'end_time' => $workTimeData['end_time'],

        ];

        $this->workTimeRepository->update($workTime->id, $updateWorkTimeRecord);
        return redirect()
            ->route('work-time.index')
            ->with('success', 'Work time updated successfully.');
    }

    public function destroy(WorkTime $workTime)
    {
        $data = $workTime->delete();
        return redirect()
            ->route('work-time.index')
            ->with('success', 'Work time has removed successfully.');
    }

}

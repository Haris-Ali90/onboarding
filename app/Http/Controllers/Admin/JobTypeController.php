<?php

namespace App\Http\Controllers\Admin;


use App\Classes\DataTableBase;
use App\Http\Requests\Admin\StoreJobTypeRequest;
use App\Http\Requests\Admin\UpdateJobTypeRequest;
use App\Models\JobType;
use App\Repositories\Interfaces\JobTypeRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class JobTypeController extends Controller
{
    private $jobTypeRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JobTypeRepositoryInterface $jobTypeRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->jobTypeRepository = $jobTypeRepository;
    }

    /**
     * List all the order category.
     *
     */

    public function index()
    {
        return view('admin.jobType.index');
    }

    /**
     * Yajra datatable call.
     *
     */

    public function data(DataTables $datatables, Request $request)
    {
        $query = JobType::query();
        $datause = $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('created_at', static function ($record) {
                return $record->created_at;
            })
            /* ->addColumn('country', static function ($user) {

                 return $user->country->name;
             })*/

            ->addColumn('action', static function ($record) {
                return backend_view('jobType.action', compact('record'));
            });

        //->rawColumns(['action'])
        $columns = ['id','title', 'created_at'];
        $base = new DataTableBase($query, $datause, $columns);
        return $base->render(null);
    }


    /**
     * Order Category create form open.
     *
     */
    public function create()
    {
        return view('admin.jobType.create');
    }


    /**
     * Store order category form data.
     *
     */
    public function store(StoreJobTypeRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        $createRecord = [
            'title' => $data['title'],
        ];

        $this->jobTypeRepository->create($createRecord);
        return redirect()
            ->route('job-type.index')
            ->with('success', 'Job Type added successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobType $job_type)
    {

        return view('admin.jobType.edit', compact('job_type'));
    }


    public function update(UpdateJobTypeRequest $request, JobType $job_type)
    {
        $exceptFields = [
            '_token',
            '_method',
        ];

        $data= $request->all();

        $updateRecord = [
            'title' => $data['title'],
        ];


        $this->jobTypeRepository->update($job_type->id, $updateRecord);

        return redirect()
            ->route('job-type.index')
            ->with('success', 'Job Type updated successfully.');
    }

    /**
     * Removes the resource from database.
     */
    public function destroy(JobType $job_type)
    {
        $this->jobTypeRepository->delete($job_type->id);
        return redirect()
            ->route('job-type.index')
            ->with('success', 'Job Type was removed successfully!');
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreTrainingRequest;
use App\Http\Requests\Admin\UpdateTrainingRequest;
use App\Models\OrderCategory;
use App\Models\Training;
use App\Models\Vendor;
use App\Repositories\Interfaces\TrainingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MicroHubTrainingController extends Controller
{
    private $trainingRepository;

    /**
     * Create a new controller instance.
     *
     * @param TrainingRepositoryInterface $trainingRepository
     */


    public function __construct(TrainingRepositoryInterface $trainingRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->trainingRepository = $trainingRepository;
    }


    public function index(Request $request)
    {
        $data = $request->all();
        //getting type to selected filter value
        $selectTrainingType = isset($data['training_type'])?$request->get('training_type'):'';
        $selectTrainingCategory = isset($data['training_category'])?$request->get('training_category'):'';

        $training_type = Training::whereNull('deleted_at')->distinct('type')->get(['id','type']);
        $training_category = OrderCategory::where('user_type','micro_hub')->whereNull('deleted_at')->distinct('id')->get(['id','name']);

        $data = Training::with('orderCategory');
        return view('admin.micro-hub.training.index', compact('data','training_type','selectTrainingType','training_category','selectTrainingCategory'));
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Training::with('microHubOrderCategory', 'vendors')->where('trainings.user_type','micro_hub')->select('trainings.*');
        if ($request->get('training_type')) {
            $query = $query->where('trainings.type',$request->get('training_type'));
        }
        if ($request->get('training_category')) {
            $query = $query->where('trainings.order_category_id',$request->get('training_category'));
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('orderCategory', static function ($record) {

                if ($record->orderCategory) {
                    return $record->orderCategory->name;
                }
                return $record = '';
            })
            ->editColumn('mandatory', static function ($record) {
                if ($record->is_compulsory!=0) {
                    return $record='Mandatory';
                }
                return $record = 'Optional';
            })
            ->editColumn('link', static function ($record) {
                return '<a href="' . $record->url . '" title="Preview" class="btn btn-xs btn-primary" target="_blank"> <i class="fa fa-link"></i></a>';
            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.training.action', compact('record'));
            })
            ->rawColumns(['link', 'orderCategory', 'is_active','mandatory'])
            ->make(true);
    }

    public function create()
    {
        $data['order_categories'] = OrderCategory::where('user_type','micro_hub')->get();

        return view('admin.micro-hub.training.create', $data);
    }

    public function store(StoreTrainingRequest $request)
    {


        $postData = $request->all();
        $title = $postData['title'];
        $order_category_id = $postData['order_category_id'];
        $duration = $postData['duration'];

        $file = $request->file('upload_file');
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendTrainingFile(), $file->getClientOriginalName());

            $postData['upload_file'] = $fileName;

        }

        if($postData['compulsory']==0){
            $is_compulsory=0;
        }else{
            $is_compulsory=1;
        }

        $insert = [
            'order_category_id' => $order_category_id,
            'is_compulsory'=>$is_compulsory,
            'vendors_id' => null,
            'name' => $file->getClientOriginalName(),
            'title' => $title,
            'description' => $file->getClientOriginalExtension(),
            'extension' => $file->getClientOriginalExtension(),
            'type' => $file->getClientMimeType(),
            'url' => url(backendTrainingFile() . $file->getClientOriginalName()),
            'duration' => $duration,
            'user_type' => 'micro_hub'
        ];
        if( $file->getClientMimeType() =='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
            $insert['type'] = 'document';
        }

        if ($file->getClientOriginalExtension() == 'mp4'){
            $insert['thumbnail'] =  url(backendTrainingFile() .'video.png');
        }

        $this->trainingRepository->create($insert);
        return redirect()
            ->route('training-list.index')
            ->with('success', 'Training added successfully.');
    }

    public function edit($training)
    {
        $training = Training::find($training);
        $order_categories = OrderCategory::where('user_type','micro_hub')->get();
      //  $vendors = Vendor::all();

        return view('admin.micro-hub.training.edit',compact('training','order_categories'));
    }

    public function update (UpdateTrainingRequest $updatetraningRequest,$training)
    {
        $postData = $updatetraningRequest->all();
        $title = $postData['title'];
        $order_category_id = $postData['category_id'];
        $duration = $postData['duration'];

        if($postData['compulsory']==0){
            $is_compulsory=0;
        }else{
            $is_compulsory=1;
        }
        $update = [
            'order_category_id' => $order_category_id,
            'is_compulsory'=>$is_compulsory,
            'duration' => $duration,
            'title' => $title,
        ];

        $file = $updatetraningRequest->file('upload_file');
        if ($updatetraningRequest->hasfile('upload_file')) {
            $file = $updatetraningRequest->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendTrainingFile(), $file->getClientOriginalName());

            $postData['upload_file'] = $fileName;

            $update['name'] = $file->getClientOriginalName();
            $update['description'] = $file->getClientOriginalExtension();
            $update['extension'] = $file->getClientOriginalExtension();
            $update['type'] = $file->getClientMimeType();
            $update['url'] = url(backendTrainingFile() . $file->getClientOriginalName());

            if( $file->getClientMimeType() =='application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                $update['type'] = 'document';
            }
            if ($file->getClientOriginalExtension() == 'mp4'){
                $update['thumbnail'] =  url(backendTrainingFile() .'video.png');
            }

        }

        $this->trainingRepository->update($training,$update);
        return redirect()
            ->route('training-list.index')
            ->with('success', 'Training updated successfully.');
    }

    public function destroy($training)
    {
        $training = Training::find($training);
        $data = $training->delete();
        return redirect()
            ->route('training-list.index')
            ->with('success', 'Training has removed successfully.');
    }


}

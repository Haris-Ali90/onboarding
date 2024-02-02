<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreJoeyChecklistRequest;
use App\Http\Requests\Admin\StoreWorkTypeRequest;
use App\Http\Requests\Admin\UpdateJoeyChecklistRequest;
use App\Http\Requests\Admin\UpdateWorkTypeRequest;
use App\Models\Categores;
use App\Models\Categories;
use App\Models\JoeyChecklist;
use App\Models\OrderCategory;
use App\Models\WorkType;
use App\Repositories\Interfaces\JoeyChecklistRepositoryInterface;
use App\Repositories\Interfaces\WorkTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JoeyChecklistController extends Controller
{
    private $joeyChecklistRepository;

    /**
     * Create a new controller instance.
     *
     * @param JoeyChecklistRepositoryInterface $joeyChecklistRepository
     */


    public function __construct(JoeyChecklistRepositoryInterface $joeyChecklistRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->joeyChecklistRepository = $joeyChecklistRepository;
    }


    public function index()
    {

        return view('admin.joeyChecklist.index');
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = JoeyChecklist::with('category')->select('joeychecklist.*');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('category', static function ($record) {
                if ($record->category) {
                    return $record->category->name;
                }
                return $record = '';
            })

            ->addColumn('action', static function ($record) {
                return backend_view('joeyChecklist.action', compact('record') );
            })
            ->rawColumns(['category','is_active'])
            ->make(true);
    }


    public function create()
    {

        $data['categories'] = OrderCategory::all();
        return view('admin.joeyChecklist.create',$data);
    }


    public function store(StoreJoeyChecklistRequest $joeyChecklistRequest){

        $data=$joeyChecklistRequest->all();

        $joeyChecklistRecord = [
            'category_id' => $data['category_id'],
            'title' => $data['title'],
        ];
        $this->joeyChecklistRepository->create($joeyChecklistRecord);
        return redirect()
            ->route('joey-checklist.index')
            ->with('success', 'Joey cheklist added successfully!');
    }


        public function edit(JoeyChecklist $joeyChecklist){
        $Ordercategories = OrderCategory::all();

            return view('admin.joeyChecklist.edit',compact('joeyChecklist','Ordercategories'));
        }


        public function update (UpdateJoeyChecklistRequest $updateJoeyChecklistRequest,JoeyChecklist $joeyChecklist){

            $data= $updateJoeyChecklistRequest->all();

            $updateJoeyChecklistRecord = [
                'category_id' => $data['category_id'],
                'title' => $data['title'],
            ];

            $this->joeyChecklistRepository->update($joeyChecklist->id, $updateJoeyChecklistRecord);
            return redirect()
                ->route('joey-checklist.index')
                ->with('success', 'Joey checklist is updated successfully!');
        }


          public function destroy(JoeyChecklist $joeyChecklist)          {

              $data = $joeyChecklist->delete();
              return redirect()
                  ->route('joey-checklist.index')
                  ->with('success', 'Joey checklist has removed successfully!');
          }

}

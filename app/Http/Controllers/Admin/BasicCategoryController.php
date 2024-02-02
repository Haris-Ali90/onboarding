<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreBasicCategoryRequest;
use App\Models\BasicCategory;
use App\Models\Categories;
use App\Repositories\Interfaces\BasicCategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BasicCategoryController extends Controller
{
    private $basicCategoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(BasicCategoryRepositoryInterface $basicCategoryRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->basicCategoryRepository = $basicCategoryRepository;
    }


    public function index()
    {

        return view('admin.basicCategory.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = BasicCategory::with('category')->select('basic_category.*');
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
                return backend_view('basicCategory.action', compact('record'));
            })
            ->rawColumns(['category,is_active'])
            ->make(true);
    }


    public function create()
    {
        $basicCategoryIds =BasicCategory::pluck('category_id');
        //dd($basicCategoryIds);
        $data['category'] = Categories::whereNotIn('id',$basicCategoryIds)->get();
        //dd($data);
        return view('admin.basicCategory.create', $data);
    }


    public function store(StoreBasicCategoryRequest $storeBasicCategoryRequest)
    {

        $data = $storeBasicCategoryRequest->all();

        $Record = [
            'category_id' => $data['category_id'],
        ];

        $this->basicCategoryRepository->create($Record);
        return redirect()
            ->route('basic-category.index')
            ->with('success', 'Basic Category added successfully!');
    }


    public function destroy(BasicCategory $basicCategory)
    {

        $data = $basicCategory->delete();
        return redirect()
            ->route('basic-category.index')
            ->with('success', 'Basic category removed successfully!');
    }

}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreCategoryScoreRequest;
use App\Http\Requests\Admin\StoreVendorScoreRequest;
use App\Http\Requests\Admin\UpdateCategoryScoreRequest;
use App\Http\Requests\Admin\UpdateVendorScoreRequest;
use App\Models\OrderCategory;
use App\Models\Vendor;
use App\Repositories\Interfaces\OrderCategoryRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryScoreController extends Controller
{
    private $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(OrderCategoryRepositoryInterface $orderCategoryRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->categoryRepository = $orderCategoryRepository;
    }


    public function index()
    {
        return view('admin.categoryScore.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = OrderCategory::whereNotNull('score');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('categoryScore.action', compact('record'));
            })
            ->rawColumns(['category', 'is_active'])
            ->make(true);
    }

    public function create()
    {
        $data['category'] = OrderCategory::whereNull('score')->get();
        return view('admin.categoryScore.create', $data);
    }


    public function store(StoreCategoryScoreRequest $storeCategoryScoreRequest)
    {
        $data = $storeCategoryScoreRequest->all();

        $record = [
            'id'=>$data['category_id'],
            'score' => $data['score'],
        ];

        $this->categoryRepository->update($data['category_id'],$record);
        return redirect()
            ->route('category-score.index')
            ->with('success', 'Category score updated successfully!');
    }

    public function edit(OrderCategory $categoryScore)
    {
        $data['category'] = OrderCategory::all();

        return view('admin.categoryScore.edit', compact('categoryScore'), $data);
    }

    public function update(UpdateCategoryScoreRequest $updateCategoryScoreRequest){
                $data = $updateCategoryScoreRequest->all();
        $record = [
            'id'=>$data['category_id'],
            'score' => $data['score'],
        ];

        $this->categoryRepository->update($data['category_id'],$record);
        return redirect()
            ->route('category-score.index')
            ->with('success', 'Category score updated successfully!');
    }

    public function destroy(Request $request)
    {
        $data = $request->all();

        $record = [
            'id'=>$data['id'],
            'score' =>null,
        ];


        $this->categoryRepository->update($data['id'],$record);
        return redirect()
            ->route('category-score.index')
            ->with('success', 'Category score was removed successfully!');
    }

}

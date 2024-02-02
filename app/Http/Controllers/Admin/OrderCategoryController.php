<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreOrderCategoryRequest;
use App\Http\Requests\Admin\UpdateOrderCategoryRequest;
use App\Models\OrderCategory;
use App\Repositories\Interfaces\OrderCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class OrderCategoryController extends Controller
{
    private $orderCategoryRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderCategoryRepositoryInterface $orderCategoryRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->orderCategoryRepository = $orderCategoryRepository;
    }

    /**
     * List all the order category.
     *
     */

    public function index()
    {
        return view('admin.orderCategory.index');
    }

    /**
     * Yajra datatable call.
     *
     */

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = OrderCategory::whereNUll('user_type');
        return $datatables->eloquent($query)
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
                return backend_view('orderCategory.action', compact('record') );
            })

            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Order Category create form open.
     *
     */
    public function create()
    {
        return view('admin.orderCategory.create');
    }


    /**
     * Store order category form data.
     *
     */
    public function store(StoreOrderCategoryRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        if($data['category_type']=='basic'){

            $createRecord = [
                'name' => $data['name'],
                'score' => $data['score'],
                'type'=> $data['category_type'],
                'order_count' => 0
            ];
        }
        else {
            $createRecord = [
                'name' => $data['name'],
                'score' => $data['score'],
                'type' => $data['category_type'],
                'order_count' => $data['count']
            ];

        }

        $this->orderCategoryRepository->create($createRecord);
        return redirect()
            ->route('order-category.index')
            ->with('success', 'Order category added successfully.');
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(OrderCategory $orderCategory)
    {

        return view('admin.order-category.show', compact('orderCategory'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(OrderCategory $orderCategory)
    {
        return view('admin.orderCategory.edit', compact('orderCategory'));
    }


    public function update(UpdateOrderCategoryRequest $request, OrderCategory $orderCategory)
    {
        $exceptFields = [
            '_token',
            '_method',
        ];

        $data= $request->all();




        if($data['category_type']=='basic'){
            $updateRecord= [
                'name' => $data['name'],
                'score' => $data['score'],
                'type'=>$data['category_type'],
                'order_count' => 0
            ];
        }
        else {
            $updateRecord = [
                'name' => $data['name'],
                'score' => $data['score'],
                'type' => $data['category_type'],
                'order_count' => $data['count']

            ];

        }





        $this->orderCategoryRepository->update($orderCategory->id, $updateRecord);

        return redirect()
            ->route('order-category.index')
            ->with('success', 'Order category updated successfully.');
    }

    /**
     * Removes the resource from database.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(OrderCategory $orderCategory)
    {
        $this->orderCategoryRepository->delete($orderCategory->id);
        return redirect()
            ->route('order-category.index')
            ->with('success', 'Order category has removed successfully!');
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCategoresRequest;
use App\Http\Requests\Admin\UpdateCategoresRequest;
use App\Models\Categores;
use App\Models\Categories;
use App\Repositories\Interfaces\CategoresRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoresController extends Controller
{
    private $categoresRepository;

    /**
     * Create a new controller instance.
     *
     * @param CategoresRepositoryInterface $categoresRepository
     */


    public function __construct(CategoresRepositoryInterface $categoresRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->categoresRepository = $categoresRepository;
    }


    public function index()
    {

        return view('admin.categores.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Categores::with('category')->select('categores.*');

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
                return backend_view('categores.action', compact('record'));
            })
            ->rawColumns(['category', 'is_active'])
            ->make(true);
    }


    public function create()
    {
        $data['categories'] = Categories::all();
        return view('admin.categores.create', $data);
    }


    public function store(StoreCategoresRequest $request)
    {
        $data = $request->all();
        $record = [
            'category_id' => $data['category_id'],
            'order_count' => $data['order_count'],
        ];

        $this->categoresRepository->create($record);
        return view('admin.categores.index');
    }


    public function edit(Categores $categore)
    {

        $data['categories'] = Categories::all();
        return view('admin.categores.edit', compact('categore'), $data);
    }

    public function update(UpdateCategoresRequest $updateCategoresRequest, Categores $categore)
    {
        $data = $updateCategoresRequest->all();
        $record = [
            'category_id' => $data['category_id'],
            'order_count' => $data['order_count'],
        ];
        $this->categoresRepository->update($categore->id, $record);
        return view('admin.categores.index');
    }

    public function destroy(Categores $categore)
    {

        $data = $categore->delete();
        return view('admin.categores.index');
    }

}

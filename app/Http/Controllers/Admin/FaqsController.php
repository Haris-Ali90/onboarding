<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreFaqsRequest;
use App\Http\Requests\Admin\UpdateFaqsRequest;
use App\Models\Documents;

use App\Models\Faqs;
use App\Models\Vendor;
use App\Repositories\Interfaces\FaqsRepositoryInterface;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FaqsController extends Controller
{
    private $faqsRepository;


    /**
     * Create a new controller instance.
     *
     * @param
     */


    public function __construct(FaqsRepositoryInterface $faqsRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->faqsRepository = $faqsRepository;
    }


    public function index()
    {
        $data=Faqs::with( 'vendors');
        return view('admin.faqs.index', compact('data'));
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Faqs::with( 'vendors')->select('faqs.*');


        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('vendors', static function ($record) {
                if ($record->vendors) {
                    return $record->vendors->name;
                }
                return $record = '';

            })
        /*    ->addColumn('vendor', static function ($record) {

                    return $record->name;

            })
            ->addColumn('order_count', static function ($record) {

                    return $record->order_count;


            })
            ->addColumn('score', static function ($record) {
                    return $record->score;

            })*/
            ->addColumn('action', static function ($record) {
                return backend_view('faqs.action', compact('record'));
            })
            ->rawColumns(['vendors'])
            ->make(true);
        }

            public function create()
            {
                $data['vendors'] = Vendor::all();

                return view('admin.faqs.create', $data);
            }

        public function store(StoreFaqsRequest $request)
        {
            $data = $request->all();

            $record = [
                'vendor_id' => $data['vendor_id'],
                'faq_title' => $data['title'],
                'faq_description' => $data['description'],

            ];

            $this->faqsRepository->create($record);

            return redirect()
                ->route('faqs.index')->with('success', 'FAQ added successfully.');

        }

 public function edit(Faqs $faq)
  {
      $vendors = Vendor::all();

      return view('admin.faqs.edit', compact('faq','vendors'));
  }

  public function update(UpdateFaqsRequest $request,Faqs $faq){



      $data = $request->all();

      $record = [
          'vendor_id' => $data['vendor_id'],
          'faq_title' => $data['title'],
          'faq_description' => $data['description'],
      ];

      $this->faqsRepository->update($faq->id, $record);
      return redirect()
          ->route('faqs.index')
          ->with('success', 'FAQ updated successfully.');
  }

  public function destroy(Faqs $faq)
  {
      $data = $faq->delete();
      return redirect()
          ->route('faqs.index')
          ->with('success', 'FAQ has deleted successfully.');
  }


}

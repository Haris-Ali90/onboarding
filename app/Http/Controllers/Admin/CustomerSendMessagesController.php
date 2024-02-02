<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCustomerSendMessagesRequest;
use App\Http\Requests\Admin\UpdateCustomerSendMessagesRequest;
use App\Models\CustomerSendMessages;
use App\Repositories\Interfaces\CustomerSendMessagesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerSendMessagesController extends Controller
{
    private $customerSendMessagesRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct(CustomerSendMessagesRepositoryInterface $customerSendMessagesRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->customerSendMessagesRepository = $customerSendMessagesRepository;
    }

    public function index()
    {
        return view('admin.customerSendMessages.index');
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = CustomerSendMessages::query();

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })

            ->addColumn('action', static function ($record) {
                return backend_view('customerSendMessages.action', compact('record') );
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.customerSendMessages.create');
    }

    public function store(StoreCustomerSendMessagesRequest $request){

        $data=$request->all();
           $messageRecord = [
            'message' => $data['message'],
                      ];

        $this->customerSendMessagesRepository->create($messageRecord);
        return redirect()
            ->route('customer-send-messages.index')
            ->with('success', 'Message added successfully.');
    }

    public function edit(CustomerSendMessages $customer_send_message){


        return view('admin.customerSendMessages.edit',compact('customer_send_message'));
    }

    public function update (UpdateCustomerSendMessagesRequest $request,CustomerSendMessages $customer_send_message){


        $data = $request->all();

           $updateRecord = [
               'message' => $data['message'],
              ];

        $this->customerSendMessagesRepository->update($customer_send_message->id, $updateRecord);
        return redirect()
            ->route('customer-send-messages.index')
            ->with('success', 'Message updated successfully.');
    }

    public function destroy(CustomerSendMessages $customer_send_message)
    {
        $data = $customer_send_message->delete();
        return redirect()
            ->route('customer-send-messages.index')
            ->with('success', 'Message has remove successfully.');
    }







}

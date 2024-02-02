<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Fcm;
use App\Http\Requests\Admin\UpdateJoeyDocumentRequest;
use App\Models\JoeyDocument;
use App\Models\Documents;
use App\Models\JoeyDocumentVerification;
use App\Models\Notification;
use App\Models\UserDevice;
use App\Repositories\Interfaces\JoeyDocumentVerificationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class JoeyDocumentVerificationController extends Controller
{
    private $joeyDocumentVerificationRepository;

    /**
     * Create a new controller instance.
     *
     * @param JoeyDocumentVerificationRepositoryInterface $joeyDocumentVerificationRepository
     */


    public function __construct(JoeyDocumentVerificationRepositoryInterface $joeyDocumentVerificationRepository,UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->joeyDocumentVerificationRepository = $joeyDocumentVerificationRepository;
        $this->userRepository = $userRepository;
    }


    public function index(Request $request)
    {
        $data = $request->all();
        $id = isset($data['id'])? $request->get('id'):'';
        $selectjoey = isset($data['joey'])?$request->get('joey'):'';
        $selectStatus = isset($data['documentStatus'])?$request->get('documentStatus'):'';
        $type = isset($data['type'])?$request->get('type'):'';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id','first_name','last_name']);
        /*$joey_document = JoeyDocument::with('joeyDetail')
        ->whereNull('joey_documents.deleted_at')
        ->groupBy('joey_documents.document_type')
        ->distinct('joey_documents.document_type')
        ->pluck('joey_documents.document_type')->toArray();*/
        $joey_document = Documents::pluck('document_name')->toArray();
        return view('admin.joeyDocumentVerification.index',compact('joeys','id','selectjoey','type','joey_document','selectStatus'));
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {

			$query = JoeyDocumentVerification::select('joey_documents.id','first_name','last_name','joey_id','joey_documents.document_type','joey_documents.exp_date','joey_documents.document_data','joey_documents.is_approved')
                ->join('joey_documents', 'joeys.id', '=', 'joey_documents.joey_id')
            ->join('document_types', 'joey_documents.document_type_id', '=', 'document_types.id')
            ->whereNull('joey_documents.deleted_at')
            ->whereNull('document_types.deleted_at');

        /*$query  =JoeyDocument::with('joeyDetail')->whereNull('joey_documents.deleted_at');*///->get();
        if ($request->get('id')) {
            $query = $query->where('joey_documents.joey_id',$request->get('id'));
        }
        if ($request->get('joey')) {
            $query = $query->where('joey_documents.joey_id',$request->get('joey'));
        }
        if ($request->get('type')) {
            $query = $query->where('joey_documents.document_type',$request->get('type'));
        }
        if ($request->documentStatus == 0 && $request->documentStatus != null)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        elseif ($request->documentStatus == 1)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        elseif($request->documentStatus == 2)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        return $datatables->eloquent($query)

            ->setRowId(static function ($record) {
                return $record->id;
            })
            //->addIndexColumn()
            ->addColumn('first_name', static function ($record) {

                if ($record) {
                    return $record->first_name.' '.$record->last_name .' ('.$record->joey_id. ')';
                }
                return '';
            })

//             ->editColumn('joey_id', static function ($record) {
//
//                return $record->joey_id;
//            })
//            ->addColumn('document_type', static function ($record) {
//                if ($record->document_type == 'driver_permit') {
//                    return 'Driver Permit';
//                }
//                else if ($record->document_type == 'driver_license') {
//                    return 'Driver License';
//                }
//                else if ($record->document_type == 'study_permit') {
//                    return 'Study Permit';
//                }
//                else if ($record->document_type == 'vehicle_insurance') {
//                    return 'Vehicle Insurance';
//                }
//                else if ($record->document_type == 'additional_document_1') {
//                    return 'Additional Document 1';
//                }
//                else if ($record->document_type == 'additional_document_2') {
//                    return 'Additional Document 2';
//                }
//                else if ($record->document_type == 'additional_document_3') {
//                    return 'Additional Document 3';
//                }
//                else {
//                    return 'Sin';
//                }
//            })
            ->editColumn('document_type', static function ($record) {
                return $record->document_type;
                /*if ($record->document_type == 'driver_permit') {
                    return 'Driver Permit';
                }
                else if ($record->document_type == 'driver_license') {
                    return 'Driver License';
                }
                else if ($record->document_type == 'study_permit') {
                    return 'Study Permit';
                }
                else if ($record->document_type == 'vehicle_insurance') {
                    return 'Vehicle Insurance';
                }
                else if ($record->document_type == 'additional_document_1') {
                    return 'Additional Document 1';
                }
                else if ($record->document_type == 'additional_document_2') {
                    return 'Additional Document 2';
                }
                else if ($record->document_type == 'additional_document_3') {
                    return 'Additional Document 3';
                }
                else {
                    return 'Sin';
                }*/
            })
            ->editColumn('exp_date', static function ($record) {
                if (!empty($record->exp_date) && $record->exp_date!='(NULL)' && $record->exp_date < date('Y-m-d')) {
                    return backend_view('joeyDocumentVerification.expiry', compact('record') );
                }
                else {
                    return $record->exp_date;
                }

            })
            ->editColumn('document_data', static function ($record) {
                return backend_view('joeyDocumentVerification.image', compact('record') );

            })
            ->editColumn('is_approved', static function ($record) {
                return backend_view('joeyDocumentVerification.status', compact('record') );

            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeyDocumentVerification.action', compact('record') );
            })
           // ->rawColumns(['is_active'])
            ->make(true);
    }

    public function statusUpdate(Request $request){

        $statusData=$request->all();

        $updateStatus = [
            'is_approved' => $statusData['status'],
        ];
        JoeyDocument::where('id' , $statusData['id'])->update($updateStatus);
        $document_rec = JoeyDocument::where('id' , $statusData['id'])->first();

        if( $document_rec->is_approved == 0 ) {
            $text = 'pending';
        }
        else if($document_rec->is_approved == 1){
            $text = 'approved';
        }
        else {
            $text = 'rejected';
        }

        $deviceIds = UserDevice::where('user_id', $document_rec->joey_id)->pluck('device_token');
        $subject =  'Document Updated ';
        $message = 'Your '.$document_rec->document_type.' has been ' . $text;
        Fcm::sendPush($subject, $message, 'document', null, $deviceIds);
        $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'document'],
            'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'document']];
        $createNotification = [
            'user_id' => $document_rec->joey_id,
            'user_type' => 'Joey',
            'notification' => $subject,
            'notification_type' => 'document',
            'notification_data' => json_encode(["body" => $message]),
            'payload' => json_encode($payload),
            'is_silent' => 0,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        Notification ::create($createNotification);


        return 'true';

    }


    public function show(JoeyDocumentVerification $joeyDocumentVerification)
    {

        $joeyDocument=JoeyDocument::where('joey_id',$joeyDocumentVerification->id)
        ->whereNull('joey_documents.deleted_at')
        ->get();
        return view('admin.joeyDocumentVerification.show', compact('joeyDocumentVerification','joeyDocument'));
    }
    public function edit(JoeyDocumentVerification $joeyDocumentVerification)
    {
    	$document_type = Documents::get();
        $joeyDocument=JoeyDocument::where('joey_id',$joeyDocumentVerification->id)->get();
        $joeyDocument_data = [];
        // setting up data
        foreach($joeyDocument as $single_joeyDocument )
        {
            $joeyDocument_data[$single_joeyDocument->document_type_id] = $single_joeyDocument;
        }
       // dd($joeyDocumentVerification);
        return view('admin.joeyDocumentVerification.edit',  compact('joeyDocumentVerification','joeyDocument_data','document_type'));
    }


    public function update(JoeyDocumentVerification $joey_document_verification,Request $request)
    {
        $document_ids = $request->document_id;
        $document_exp_date = $request->document_exp_date;
        $document_status = $request->document_status;
        $documents =  $request->document;
        $document_type_name = $request->document_type_name;
        $current_data = date('Y-m-d H:i:s');
        if(!empty($documents))
        {
            $selected_data = [];
            foreach ($documents as $key => $document) {

                $document_data ='';
                if(gettype($document) == 'object')
                {
                    $imagedata = file_get_contents($document);
                    $base64 = base64_encode($imagedata);
                    if (!empty($base64)) {
                        $document_data = $this->upload2($base64);
                        if (!isset($document_data)) {
                            return redirect()->back()->with('success', 'Document not uploaded due to server error.');
                        }
                    }
//                    $file = $document;
//                    $fileName = $file->getClientOriginalName();
//                    $file->move(backendUserFile(), $file->getClientOriginalName());
//                    $document_data = url(backendUserFile() . $fileName);
                }
                else
                {
                    $document_data = $document;
                }

                // getting updating ids
                $id = $document_ids[$key];
                // deleting old data for duplication
                if($document_ids[$key] > 0){
                    $joey_document_data = JoeyDocument::where('id',$id)->update(['deleted_at' => $current_data]);
                }

                // duplication data
                $joey_document_data_new_data = JoeyDocument::create([
                    "joey_id" =>$joey_document_verification->id,
                    "document_type" =>$document_type_name[$key],
                    "document_type_id" => $key,
                    "document_data" => $document_data,
                    "exp_date" => $document_exp_date[$key],
                    "is_approved" => isset($document_status[$key]) ? $document_status[$key]: 0
                ]);

                $document_rec = JoeyDocument::where('id' , $joey_document_data_new_data->id)->first();

                if( $document_rec->is_approved == 0 ) {
                    $text = 'pending';
                }
                else if($document_rec->is_approved == 1){
                    $text = 'approved';
                }
                else {
                    $text = 'rejected';
                }

                $deviceIds = UserDevice::where('user_id', $joey_document_verification->id)->pluck('device_token');
                $subject =  'Document Updated ';
                $message = 'Your '.$document_rec->document_type.' has been ' . $text;
                Fcm::sendPush($subject, $message, 'document', null, $deviceIds);
                $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'document'],
                    'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'document']];
                $createNotification = [
                    'user_id' => $joey_document_verification->id,
                    'user_type' => 'Joey',
                    'notification' => $subject,
                    'notification_type' => 'document',
                    'notification_data' => json_encode(["body" => $message]),
                    'payload' => json_encode($payload),
                    'is_silent' => 0,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                Notification ::create($createNotification);
            }

        }
        


        return redirect()
            ->route('joey-document-verification.index')
            ->with('success', 'Document updated successfully.');
    }
    

    public function expiredDocument(Request $request)
    {
        
        $data = $request->all();
        $id = isset($data['id'])? $request->get('id'):'';
        $selectjoey = isset($data['joey'])?$request->get('joey'):'';
        $selectStatus = isset($data['documentStatus'])?$request->get('documentStatus'):'';
        $type = isset($data['type'])?$request->get('type'):'';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id','first_name','last_name']);
        /*$joey_document = JoeyDocument::with('joeyDetail')
        ->whereNull('joey_documents.deleted_at')
        ->groupBy('joey_documents.document_type')
        ->distinct('joey_documents.document_type')
        ->pluck('joey_documents.document_type')->toArray();*/
        $joey_document = Documents::pluck('document_name')->toArray();
        return view('admin.joeyDocumentVerification.expired-document',compact('joeys','id','selectjoey','type','joey_document','selectStatus'));
    }

    public function expiredData(DataTables $datatables, Request $request) : JsonResponse
    {
        $today_date = Carbon::now()->format('Y-m-d h:m:s');

       $query = JoeyDocumentVerification::select('joey_documents.id','first_name','last_name','joey_id','joey_documents.document_type','joey_documents.exp_date','joey_documents.document_data','joey_documents.is_approved')
       		->join('joey_documents', 'joeys.id', '=', 'joey_documents.joey_id')
            ->join('document_types', 'joey_documents.document_type_id', '=', 'document_types.id')
            ->where('joey_documents.exp_date', '<' , $today_date)
            ->whereNotNull('joey_documents.exp_date')
            ->where('joey_documents.exp_date','!=','(NULL)')
            ->whereNull('joey_documents.deleted_at')
            ->whereNull('document_types.deleted_at');

        /*$query  =JoeyDocument::with('joeyDetail')->whereNull('joey_documents.deleted_at');*///->get();
        if ($request->get('id')) {
            $query = $query->where('joey_documents.joey_id',$request->get('id'));
        }
        if ($request->get('joey')) {
            $query = $query->where('joey_documents.joey_id',$request->get('joey'));
        }
        if ($request->get('type')) {
            $query = $query->where('joey_documents.document_type',$request->get('type'));
        }
        if ($request->documentStatus == 0 && $request->documentStatus != null)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        elseif ($request->documentStatus == 1)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        elseif($request->documentStatus == 2)
        {
            $query = $query->where('joey_documents.is_approved',$request->documentStatus);
        }
        return $datatables->eloquent($query)

            ->setRowId(static function ($record) {
                return $record->id;
            })
            //->addIndexColumn()
            ->addColumn('joeyDetail', static function ($record) {

                if ($record) {
                    return $record->first_name.' '.$record->last_name .' ('.$record->joey_id. ')';
                }
                return '';
            })

//             ->editColumn('joey_id', static function ($record) {
//
//                return $record->joey_id;
//            })
//            ->addColumn('document_type', static function ($record) {
//                if ($record->document_type == 'driver_permit') {
//                    return 'Driver Permit';
//                }
//                else if ($record->document_type == 'driver_license') {
//                    return 'Driver License';
//                }
//                else if ($record->document_type == 'study_permit') {
//                    return 'Study Permit';
//                }
//                else if ($record->document_type == 'vehicle_insurance') {
//                    return 'Vehicle Insurance';
//                }
//                else if ($record->document_type == 'additional_document_1') {
//                    return 'Additional Document 1';
//                }
//                else if ($record->document_type == 'additional_document_2') {
//                    return 'Additional Document 2';
//                }
//                else if ($record->document_type == 'additional_document_3') {
//                    return 'Additional Document 3';
//                }
//                else {
//                    return 'Sin';
//                }
//            })
            ->editColumn('document_type', static function ($record) {
                return $record->document_type;
                /*if ($record->document_type == 'driver_permit') {
                    return 'Driver Permit';
                }
                else if ($record->document_type == 'driver_license') {
                    return 'Driver License';
                }
                else if ($record->document_type == 'study_permit') {
                    return 'Study Permit';
                }
                else if ($record->document_type == 'vehicle_insurance') {
                    return 'Vehicle Insurance';
                }
                else if ($record->document_type == 'additional_document_1') {
                    return 'Additional Document 1';
                }
                else if ($record->document_type == 'additional_document_2') {
                    return 'Additional Document 2';
                }
                else if ($record->document_type == 'additional_document_3') {
                    return 'Additional Document 3';
                }
                else {
                    return 'Sin';
                }*/
            })
            ->editColumn('exp_date', static function ($record) {
                if (!empty($record->exp_date) && $record->exp_date!='(NULL)' && $record->exp_date < date('Y-m-d')) {
                    return backend_view('joeyDocumentVerification.expiry', compact('record') );
                }
                else {
                    return $record->exp_date;
                }

            })
            ->editColumn('document_data', static function ($record) {
                return backend_view('joeyDocumentVerification.image', compact('record') );

            })
            ->editColumn('is_approved', static function ($record) {
                return backend_view('joeyDocumentVerification.status', compact('record') );

            })
            /*->addColumn('action', static function ($record) {
                return backend_view('joeyDocumentVerification.action', compact('record') );
            })*/
           // ->rawColumns(['is_active'])
            ->make(true);
    }

    public function upload2($base64Data) {
        //   $request = new Image_JsonRequest();
        $data = ['image' => $base64Data];
        $response = $this->sendData2('POST', '/',  $data );
        if(!isset($response->url)) {
            return null;

        }
        return $response->url;
    }

    public function sendData2($method, $uri, $data = [])
    {
        $host = 'ap2uploads.joeyco.com';

        $json_data = json_encode($data);

        $headers = [
            'Accept-Encoding: utf-8',
            'Accept: application/json; charset=UTF-8',
            'Content-Type: application/json; charset=UTF-8',
            'User-Agent: JoeyCo',
            'Host: ' . $host,
        ];

        if (!empty($json_data)) {

            $headers[] = 'Content-Length: ' . strlen($json_data);
        }

        $url = 'https://' . $host . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (strlen($json_data) > 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        if (env('APP_ENV') === 'local') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        set_time_limit(0);

        $this->originalResponse = curl_exec($ch);

        $error = curl_error($ch);

        curl_close($ch);

        if (empty($error)) {

            $this->response = explode("\n", $this->originalResponse);

            $code = explode(' ', $this->response[0]);
            $code = $code[1];

            $this->response = $this->response[count($this->response) - 1];
            $this->response = json_decode($this->response);

            if (json_last_error() != JSON_ERROR_NONE) {
                return redirect()->back()->with('success', 'Document not uploaded due to server error.');
            }
        }

        return $this->response;
    }


}

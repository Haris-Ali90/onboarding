<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\StoreVendorsCountRequest;
use App\Http\Requests\Admin\UpdateVendorsCountRequest;
use App\Models\Documents;
use App\Models\Vendor;
use App\Models\Vendors;
use App\Repositories\Interfaces\DocumentsRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use App\Repositories\Interfaces\VendorsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MicroHubDocumentsController extends Controller
{
    private $documentsRepository;

    /**
     * Create a new controller instance.
     *
     * @param
     */
    public function __construct(DocumentsRepositoryInterface $documentsRepository)
    {
        parent::__construct();
        $this->documentsRepository = $documentsRepository;
    }

    public function documentList()
    {
        return view('admin.micro-hub.documents.index');
    }

    public function documentData(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Documents::where('user_type', 'micro_hub');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('is_optional', static function ($record) {

                $context = 'No';
                if ($record->is_optional == 0) {
                    $context = 'Yes';
                }
                return $context;
            })
            ->editColumn('exp_date', static function ($record) {

                $context = 'No';
                if ($record->exp_date == 1) {
                    $context = 'Yes';
                }
                return $context;
            })
            ->editColumn('upload_option', static function ($record) {

                $context = 'No';
                if ($record->upload_option == 1) {
                    $context = 'Yes';
                }
                return $context;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.documents.action', compact('record'));
            })
            ->rawColumns([])
            ->make(true);
    }

    public function documentCreate()
    {
        $data = Documents::all();

        return view('admin.micro-hub.documents.create', $data);
    }

    public function documentStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/|max:50',
            'file_type' => '',
            'document_type' => '',
            'is_expiry_date' => '',
            'option' => '',
            'max_characters_limit' => ''

        ]);

        if ($data['document_type'] == 'text') {

            $record = [
                'document_name' => $data['name'],
                'document_type' => $data['document_type'],
                'is_optional' => $data['file_type'],
                'exp_date' => $data['is_expiry_date'],
                'upload_option' => $data['option'],
                'max_characters_limit' => $data['max_characters_limit'],
                'user_type' => 'micro_hub'
            ];

        } else {
            $record = [
                'document_name' => $data['name'],
                'document_type' => $data['document_type'],
                'is_optional' => $data['file_type'],
                'exp_date' => $data['is_expiry_date'],
                'upload_option' => $data['option'],
                'max_characters_limit' => 0,
                'user_type' => 'micro_hub'
            ];

        }

        $this->documentsRepository->create($record);

        return redirect()
            ->route('micro-hub.documentList.index')
            ->with('success', 'Document added successfully.');
    }

    public function documentEdit(Documents $document)
    {

        return view('admin.micro-hub.documents.edit', compact('document'));
    }

    public function documentUpdate(Request $request, Documents $document)
    {
        $data = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/|max:50',
            'file_type' => '',
            'document_type' => '',
            'is_expiry_date' => '',
            'option' => '',
            'max_characters_limit' => ''
        ]);

        if ($data['document_type'] == 'text') {

            $record = [
                'document_name' => $data['name'],
                'document_type' => $data['document_type'],
                'is_optional' => $data['file_type'],
                'exp_date' => $data['is_expiry_date'],
                'upload_option' => $data['option'],
                'max_characters_limit' => $data['max_characters_limit'],
                'user_type' => 'micro_hub'
            ];

        } else {
            $record = [
                'document_name' => $data['name'],
                'document_type' => $data['document_type'],
                'is_optional' => $data['file_type'],
                'exp_date' => $data['is_expiry_date'],
                'upload_option' => $data['option'],
                'max_characters_limit' => 0,
                'user_type' => 'micro_hub'
            ];

        }

        $this->documentsRepository->update($document->id, $record);

        return redirect()
            ->route('micro-hub.documentList.index')
            ->with('success', 'Document updated successfully.');
    }

    public function documentTypeDestroy(Documents $document)
    {
        $data = $document->delete();
        return redirect()
            ->route('micro-hub.documentList.index')
            ->with('success', 'Document has removed successfully.');
    }

}

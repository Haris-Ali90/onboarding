<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreZoneRequest;
use App\Http\Requests\Admin\UpdateZoneRequest;
use App\Models\Zone;
use App\Repositories\Interfaces\ZoneRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ZonesController extends Controller
{
    private $zoneRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct(ZoneRepositoryInterface $zoneRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->zoneRepository = $zoneRepository;
    }

    public function index()
    {
        return view('admin.zones.index');
    }

    public function data(DataTables $datatables, Request $request) : JsonResponse
    {
        $query  = Zone::query();

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })

            ->addColumn('action', static function ($record) {
                return backend_view('zones.action', compact('record') );
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(StoreZoneRequest $request){

        $data=$request->all();

        $latitude = str_replace(".", "", $data['latitude']);
        $latitudes = (strlen($latitude) > 10) ? (int)substr($latitude, 0, 9) : (int)$latitude;
        $lng = strlen((int)$data['longitude']);
        if ($lng == 4){
            $longitude = str_replace(".", "", $data['longitude']);
            $longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 10) : (int)$longitude;
        }
        else{
            $longitude = str_replace(".", "", $data['longitude']);

            $longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 9) : (int)$longitude;
        }

        $zoneRecord = [
            'name' => $data['name'],
            'latitude' => $latitudes,
            'longitude' => $longitudes,
            'radius' => $data['radius'],
            'timezone' =>$data['time_zone'],

            ];
       // dd($zoneRecord);
        $this->zoneRepository->create($zoneRecord);
        return redirect()
            ->route('zones.index')
            ->with('success', 'Zone added successfully.');
    }

    public function edit(Zone $zone){
        return view('admin.zones.edit',compact('zone'));
    }

    public function update (UpdateZoneRequest $updateZoneRequest,Zone $zone){


        $zoneData = $updateZoneRequest->all();

        $lng = strlen((int)$zoneData['longitude']);



        $latitude = str_replace(".", "", $zoneData['latitude']);
        $latitudes = (strlen($latitude) > 10) ? (int)substr($latitude, 0, 9) : (int)$latitude;

        if ($lng == 4){
            //dd([$lng, $zoneData['longitude'], $latitudes]);
            $longitude = str_replace(".", "", $zoneData['longitude']);
            $longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 10) : (int)$longitude;
        }
        else{
           // dd([$lng, $zoneData['longitude']]);
            $longitude = str_replace(".", "", $zoneData['longitude']);
            $longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 9) : (int)$longitude;
        }


        $updateZoneRecord = [
            'name' => $zoneData['name'],
            'latitude' => $latitudes,
            'longitude' => $longitudes,
            'radius' => $zoneData['radius'],
            'timezone' =>$zoneData['time_zone'],
        ];

        $this->zoneRepository->update($zone->id, $updateZoneRecord);
        return redirect()
            ->route('zones.index')
            ->with('success', 'Zone updated successfully.');
    }

    public function destroy(Zone $zone)
    {
        $data = $zone->delete();
        return redirect()
            ->route('zones.index')
            ->with('success', 'Zone has removed successfully.');
    }







}

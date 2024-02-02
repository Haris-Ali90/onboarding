<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Fcm;
use App\Events\Backend\DocumentNotUploadEvent;
use App\Events\Backend\NotTrainedEvent;
use App\Events\Backend\QuizPendingEvent;
use App\Models\AgreementUser;
use App\Models\Cities;
use App\Models\Hubs;
use App\Models\JoeycoUsers;
use App\Models\JoeyDeposit;
use App\Models\JoeyDocument;
use App\Models\JoeyDocumentVerification;
use App\Models\JoeyMetaData;
use App\Models\JoeyPlans;
use App\Models\Locations;
use App\Models\Notification;
use App\Models\UserDevice;
use App\Models\Vehicle;
use App\Models\VehicleDetail;
use App\Models\Zone;
use App\Repositories\Interfaces\JoeyDocumentVerificationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoeyCo\Http\Code;
use stdClass;
use Twilio\Rest\Client;
use Yajra\DataTables\DataTables;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Repositories\Interfaces\LocationsRepositoryInterface;

class JoeyController extends Controller
{
    private $joeyDocumentVerificationRepository;
    private $locationRepository;
    private $locationEncRepository;

    /**
     * Create a new controller instance.
     *
     * @param JoeyDocumentVerificationRepositoryInterface $joeyDocumentVerificationRepository
     */
    public function __construct(JoeyDocumentVerificationRepositoryInterface $joeyDocumentVerificationRepository, UserRepositoryInterface $userRepository,LocationRepositoryInterface $locationRepository,
                                LocationsRepositoryInterface $locationEncRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->joeyDocumentVerificationRepository = $joeyDocumentVerificationRepository;
        $this->userRepository = $userRepository;
        $this->locationRepository = $locationRepository;
        $this->locationEncRepository = $locationEncRepository;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $enabled = isset($data['is_enabled']) ? $request->get('is_enabled') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;
        return view('admin.joeys.index', compact('selectjoey', 'joeys', 'old_request_data', 'enabled'));
        // return view('admin.joeys.joey-application-numbers');
    }

    public function statistics(Request $request)
    {
        $request_data = $request->all();
        if (auth()->user()->login_type == "micro_hub") {
            if (!empty($request['days'])) {

                if ($request['days'] == 'lastweek') {

                    $startOfCurrentWeek = Carbon::now()->startOfWeek();
                    $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
                    $currentDate = $currentDate . ' 23:59:59';
                    /**
                     * New Hub Request Count
                     * */
                    $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Activated Hub Request Count
                     * */
                    $activatedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('dashboard_users.role_id', 5)
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Approved Hub Request Count
                     * */
                    $approvedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 1)
                        ->where('dashboard_users.role_id', 5);

                    /**
                     * Declined Hub Request Count
                     * */
                    $declinedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 2);

                    if ($request['zone'] != null) {

                        $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request->zone);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('jc_users.city', $request->zone);
                        $approvedHubRequestCount = $approvedHubRequestCount->where('jc_users.city', $request->zone);
                        $declinedHubRequestCount = $declinedHubRequestCount->where('jc_users.city', $request->zone);
                    }
                    if ($request['area_radius'] != null) {

//                        $newHubRequestCount = $newHubRequestCount->where('micro_hub_request.area_radius', $request->area_radius);
                        $newHubRequestCount = $newHubRequestCount            ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $approvedHubRequestCount = $approvedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $declinedHubRequestCount = $declinedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                    }

                    $newHubRequestCount = $newHubRequestCount->count();
                    $activatedHubRequestCount = $activatedHubRequestCount->count();
                    $approvedHubRequestCount = $approvedHubRequestCount->count();
                    $declinedHubRequestCount = $declinedHubRequestCount->count();


                } elseif ($request['days'] == 'onemonth') {

                    $firstDayofPreviousMonth = Carbon::now()->subDays(30)->format('Y-m-d');
                    $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');

                    $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
                    $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
                    /*$firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();

                    $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();*/


                    /**
                     * New Hub Request Count
                     * */
                    $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Activated Hub Request Count
                     * */
                    $activatedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('dashboard_users.role_id', 5)
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Approved Hub Request Count
                     * */
                    $approvedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 1)
                        ->where('dashboard_users.role_id', 5);

                    /**
                     * Declined Hub Request Count
                     * */
                    $declinedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 2);
                    if ($request['zone'] != null) {

                        $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request->zone);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('jc_users.city', $request->zone);
                        $approvedHubRequestCount = $approvedHubRequestCount->where('jc_users.city', $request->zone);
                        $declinedHubRequestCount = $declinedHubRequestCount->where('jc_users.city', $request->zone);
                    }
                    if ($request['area_radius'] != null) {

                        $newHubRequestCount = $newHubRequestCount            ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $approvedHubRequestCount = $approvedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $declinedHubRequestCount = $declinedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                    }

                    $newHubRequestCount = $newHubRequestCount->count();
                    $activatedHubRequestCount = $activatedHubRequestCount->count();
                    $approvedHubRequestCount = $approvedHubRequestCount->count();
                    $declinedHubRequestCount = $declinedHubRequestCount->count();

                } elseif ($request['days'] == 'sixmonth') {

                    $firstDayofPreviousMonth = Carbon::now()->subDays(180)->format('Y-m-d');
                    $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
//                    $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth(6)->toDateString();
//                    $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

                    $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
                    $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
                    /**
                     * New Hub Request Count
                     * */
                    $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Activated Hub Request Count
                     * */
                    $activatedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('dashboard_users.role_id', 5)
                        ->where('micro_hub_request.status', 0);

                    /**
                     * Approved Hub Request Count
                     * */
                    $approvedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 1)
                        ->where('dashboard_users.role_id', 5);

                    /**
                     * Declined Hub Request Count
                     * */
                    $declinedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                        ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('micro_hub_request.deleted_at')
                        ->where('micro_hub_request.status', 2);

                    if ($request['zone'] != null) {

                        $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request->zone);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('jc_users.city', $request->zone);
                        $approvedHubRequestCount = $approvedHubRequestCount->where('jc_users.city', $request->zone);
                        $declinedHubRequestCount = $declinedHubRequestCount->where('jc_users.city', $request->zone);
                    }
                    if ($request['area_radius'] != null) {

                        $newHubRequestCount = $newHubRequestCount            ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $activatedHubRequestCount = $activatedHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $approvedHubRequestCount = $approvedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                        $declinedHubRequestCount = $declinedHubRequestCount  ->where('micro_hub_request.area_radius', '<=', $request->area_radius);
                    }


                    $newHubRequestCount = $newHubRequestCount->count();
                    $activatedHubRequestCount = $activatedHubRequestCount->count();
                    $approvedHubRequestCount = $approvedHubRequestCount->count();
                    $declinedHubRequestCount = $declinedHubRequestCount->count();


                }


            } else {

                $startOfCurrentWeek = Carbon::now()->startOfWeek();
                $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
                $currentDate = Carbon::now()->format('Y-m-d');

                $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
                $currentDate = $currentDate . ' 23:59:59';
                /**
                 * New Hub Request Count
                 * */
                $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                    ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                    ->whereNull('micro_hub_request.deleted_at')
                    ->where('micro_hub_request.status', 0)
                    ->count();

                /**
                 * Activated Hub Request Count
                 * */
                $activatedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                    ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                    ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                    ->whereNull('micro_hub_request.deleted_at')
                    ->where('micro_hub_request.status', 0)
                    ->where('dashboard_users.role_id', 5)
                    ->count();

                /**
                 * Approved Hub Request Count
                 * */
                $approvedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                    ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
                    ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                    ->whereNull('micro_hub_request.deleted_at')
                    ->where('micro_hub_request.status', 1)
                    ->where('dashboard_users.role_id', 5)
                    ->count();

                /**
                 * Declined Hub Request Count
                 * */
                $declinedHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
                    ->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                    ->whereNull('micro_hub_request.deleted_at')
                    ->where('micro_hub_request.status', 2)
                    ->count();

            }

            // //Get zone From Request
            $selectzone = isset($request['zone']) ? $request->get('zone') : '';
            // //Get Area Ra From Request
            $areaRadius = isset($data['status']) ? $request->get('status') : '';
            $zone = Zone::whereNull('deleted_at')->get(['id', 'name']);

            return view('admin.joeys.joey-application-numbers', compact('areaRadius', 'zone', 'selectzone',/*'email','status'*/ 'newHubRequestCount', 'activatedHubRequestCount', 'approvedHubRequestCount', 'declinedHubRequestCount'));
        }
        else {
            // checking the request exsit
            if (!empty($request_data)) {

                if ($request_data['days'] == '3days') {
                    $date = Carbon::now()->subDays(3)->format('Y-m-d');
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $date = $date . ' 00:00:00';
                    $currentDate = $currentDate . ' 23:59:59';

                    $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])->count();

                    $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])->count();

                    $percentage = 0;
                    if ($totalSignUps > 0 && $basicRegistration > 0) {
                        $percentage = round($basicRegistration / $totalSignUps * 100);
                    } else {
                        $percentage = 1;
                    }

                    /**
                     * application submission calcultion
                     * */
                    $totalApplicationSubmissionCount = JoeyDocumentVerification::whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                         ->whereNull('deleted_at')
                        ->where('is_active', 1)
                        ->count();

                    /**
                     * doucemnt submission calcultion
                     * */
                    $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_documents.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->whereNull('joey_documents.deleted_at')
                        ->count();

                    /**
                     * trainingwatcehd
                     * */
                    $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_training_seen.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->count();

                    /**
                     * quiz passed
                     * */

                    $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_attempted_quiz.joey_id')
                        ->whereNull('joey_attempted_quiz.deleted_at')
                        ->where('joey_attempted_quiz.is_passed', 1)
                        ->count();


                } elseif ($request_data['days'] == 'lastweek') {


                    $startOfCurrentWeek = Carbon::now()->startOfWeek();
                    $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
                    $currentDate = $currentDate . ' 23:59:59';
                    $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])->count();
                    $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])->count();

                    $percentage = 0;
                    if ($totalSignUps > 0 && $basicRegistration > 0) {
                        $percentage = round($basicRegistration / $totalSignUps * 100);
                    } else {
                        $percentage = 1;
                    }
                    /**
                     * application submission calcultion
                     * */
                    $totalApplicationSubmissionCount = JoeyDocumentVerification::whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->whereNull('deleted_at')
                        ->where('is_active', 1)
                        ->count();

                    /**
                     * doucemnt submission calcultion
                     * */
                    $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_documents.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->whereNull('joey_documents.deleted_at')
                        ->count();

                    /**
                     * trainingwatcehd
                     * */
                    $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_training_seen.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->count();

                    /**
                     * quiz passed
                     * */
                    $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_attempted_quiz.joey_id')
                        ->whereNull('joey_attempted_quiz.deleted_at')
                        ->where('joey_attempted_quiz.is_passed', 1)
                        ->count();

                } elseif ($request_data['days'] == '15days') {

                    $date = Carbon::now()->subDays(15)->format('Y-m-d');
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $date = $date . ' 00:00:00';
                    $currentDate = $currentDate . ' 23:59:59';

                    $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])->count();

                    $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])->count();
                    $percentage = 0;
                    if ($totalSignUps > 0 && $basicRegistration > 0) {
                        $percentage = round($basicRegistration / $totalSignUps * 100);
                    } else {
                        $percentage = 1;
                    }
                    /**
                     * application submission calcultion
                     * */
                    $totalApplicationSubmissionCount = JoeyDocumentVerification::whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->whereNull('deleted_at')
                        ->where('is_active', 1)
                        ->count();

                    /**
                     * doucemnt submission calcultion
                     * */
                    $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_documents.joey_id')
                        ->whereNull('joey_documents.deleted_at')
                        ->count();

                    /**
                     * trainingwatcehd
                     * */
                    $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_training_seen.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->count();

                    /**
                     * quiz passed
                     * */
                    $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_attempted_quiz.joey_id')
                        ->whereNull('joey_attempted_quiz.deleted_at')
                        ->where('joey_attempted_quiz.is_passed', 1)
                        ->count();

                } elseif ($request_data['days'] == 'lastmonth') {

                    $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();

                    $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

                    $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
                    $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';

                    $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])->count();

                    $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')
                        ->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])->count();
                    $percentage = 0;
                    if ($totalSignUps > 0 && $basicRegistration > 0) {
                        $percentage = round($basicRegistration / $totalSignUps * 100);
                    } else {
                        $percentage = 1;
                    }

                    /**
                     * application submission calcultion
                     * */
                    $totalApplicationSubmissionCount = JoeyDocumentVerification::whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->whereNull('deleted_at')
                        ->where('is_active', 1)
                        ->count();

                    /**
                     * doucemnt submission calcultion
                     * */
                    $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_documents.joey_id')
                        ->whereNull('joey_documents.deleted_at')
                        ->count();

                    /**
                     * trainingwatcehd
                     * */
                    $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_training_seen.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->count();

                    /**
                     * quiz passed
                     * */
                    $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                        ->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_attempted_quiz.joey_id')
                        ->whereNull('joey_attempted_quiz.deleted_at')
                        ->where('joey_attempted_quiz.is_passed', 1)
                        ->count();


                } elseif ($request_data['days'] == 'all') {

                    $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')->count();
                    $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')->count();
                    if ($totalSignUps > 0 && $basicRegistration > 0) {
                        $percentage = round($basicRegistration / $totalSignUps * 100);
                    } else {
                        $percentage = 1;
                    }
                    /**
                     * application submission calcultion
                     * */
                    $totalApplicationSubmissionCount = JoeyDocumentVerification::where('joeys.is_active', 1)->whereNull('deleted_at')->count();
                    /**
                     * Document submission calcultion
                     * */
                    $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_documents.joey_id')
                        ->whereNull('joey_documents.deleted_at')
                        ->count();
                    /**
                     * trainingwatcehd
                     * */
                    $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                        ->where('joeys.is_active', 1)
                        ->distinct('joey_training_seen.joey_id')
                        ->whereNull('joeys.deleted_at')
                        ->count();
                    /**
                     * quiz passed
                     * */
                    $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                        ->where('joeys.is_active', 1)
                        ->whereNull('joeys.deleted_at')
                        ->distinct('joey_attempted_quiz.joey_id')
                        ->whereNull('joey_attempted_quiz.deleted_at')
                        ->where('joey_attempted_quiz.is_passed', 1)
                        ->count();

                }
            }
            else {

                $totalSignUps = JoeyDocumentVerification::whereNull('deleted_at')->count();
                $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at')->count();

                if ($totalSignUps > 0 && $basicRegistration > 0) {
                    $percentage = round($basicRegistration / $totalSignUps * 100);
                } else {
                    $percentage = 1;
                }
                /**
                 * application submission calcultion
                 * */
                $totalApplicationSubmissionCount = JoeyDocumentVerification::where('joeys.is_active', 1)->whereNull('deleted_at')->count();
                /**
                 * Document submission calcultion
                 * */
                $totalDocumentSubmissionCount = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
                    ->where('joeys.is_active', 1)
                    ->distinct('joey_documents.joey_id')
                    ->whereNull('joeys.deleted_at')
                    ->whereNull('joey_documents.deleted_at')
                    ->count();
                /**
                 * trainingwatcehd
                 * */
                $totalTrainingwatchedCount = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
                    ->where('joeys.is_active', 1)
                    ->distinct('joey_training_seen.joey_id')
                    ->whereNull('joeys.deleted_at')
                    ->count();
                /**
                 * quiz passed
                 * */
                $totalQuizPassedCount = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
                    ->where('joeys.is_active', 1)
                    ->whereNull('joeys.deleted_at')
                    ->distinct('joey_attempted_quiz.joey_id')
                    ->whereNull('joey_attempted_quiz.deleted_at')
                    ->where('joey_attempted_quiz.is_passed', 1)
                    ->count();

            }
            return view('admin.joeys.joey-application-numbers', compact('percentage', 'totalSignUps', 'totalQuizPassedCount', 'totalTrainingwatchedCount', 'totalApplicationSubmissionCount'
                , 'totalDocumentSubmissionCount', 'basicRegistration'));

        }
    }

    /**
     * Call DataTable For List
     *
     */
    public function microHubData(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeycoUsers::select('jc_users.*', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status')
            ->join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->whereNull('micro_hub_request.deleted_at');

        //Filter Data By Email
        if ($request->email) {
            $query = $query->where('jc_users.email_address', $request->email);
        }
        //Filter Data By Phone
        if ($request->zone) {
            $query = $query->where('jc_users.city', $request->zone);
        }

        if ($request->status && $request->status != 'null') {
            $status = $request->status;
            if ($status == 1) {
                $status = 0;
            } elseif ($status == 2) {
                $status = 1;
            } elseif ($status == 3) {
                $status = 2;
            }
            $query = $query->where('micro_hub_request.status', $status);
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('own_joeys', static function ($record) {
                if ($record->own_joeys == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->editColumn('status', static function ($record) {
                if ($record->status == 1) {
                    return 'Approved';
                } elseif ($record->status == 2) {
                    return 'Declined';
                } elseif ($record->status == 0) {
                    return 'Pending';
                }
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeyDocumentVerification::whereNull('deleted_at');//->orderBy('first_name','asc');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }

        if ($request->get('is_enabled') == 0) {
            $query->where('joeys.is_enabled', $request->get('is_enabled'));
            //$query->where('is_enabled',0);
        } else {
            $query->where('is_enabled', 1);
        }

        //dd($request->get('is_enabled'));
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addIndexColumn()
            ->addColumn('first_name', static function ($record) {

                return $record->first_name . ' ' . $record->last_name;

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->editColumn('is_active', static function ($record) {
                if ($record->is_active != 0) {
                    return 'Sign-Up Steps Completed';
                }
                return 'Sign-Up Steps Pending';

            })
            ->editColumn('document_verification', static function ($record) {
                if (count($record->joeyDocumentsApproved) > 0) {
                    return 'Verification Completed';
                }
                return 'Verification Pending';

            })
            ->editColumn('training_completion', static function ($record) {
                if (count($record->trainingSeen) > 0) {
                    return 'Training Completed';
                }
                return 'Training Pending';

            })
            ->editColumn('quiz_completion', static function ($record) {
                if (count($record->joeyAttemptedQuiz) > 0) {
                    return 'Passed';
                }
                return 'Failed';

            })
            ->editColumn('image', static function ($record) {
                return backend_view('joeys.image', compact('record'));

            })
            ->editColumn('Signed At', static function ($record) {

                $agreements_signed = AgreementUser::where('user_id', $record->id)->whereNotNull('signed_at')->pluck('user_id')->toArray();
                if (!empty($agreements_signed)) {
                    return $record->created_at;
                }
                return 'No';

            })
            ->editColumn('status', static function ($record) {
                return backend_view('joeys.status', compact('record'));

            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeys.action', compact('record'));
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function active(JoeyDocumentVerification $record)
    {
        $this->joeyDocumentVerificationRepository->update($record->id, ['is_enabled' => 1]);
        //$record->activate();
        return redirect()
            ->route('joeys-list.index')
            ->with('success', 'Joey has been active successfully!');
    }

    public function inactive(JoeyDocumentVerification $record)
    {
        $this->joeyDocumentVerificationRepository->update($record->id, ['is_enabled' => 0]);
        //$record->deactivate();
        return redirect()
            ->route('joeys-list.index')
            ->with('success', 'Joey has been inactive successfully!');
    }

    //New Sign Up Joeys
    public function newSignUpJoeys(Request $request)
    {
        $data = $request->all();
        // setting old request data
        $old_request_data = $data;
        return view('admin.joeys.new-sign-up-joeys-list', compact('old_request_data'));
        // return view('admin.joeys.joey-application-numbers');
    }

    public function newSignUpJoeysData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::query();//->orderBy('first_name','asc');
        if ($request->start_date && $request->end_date) {
            $query = $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addIndexColumn()
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->editColumn('referral_id', static function ($record) {
                return '';

            })
            ->editColumn('referral_name', static function ($record) {
                return '';

            })
            ->rawColumns([])
            ->make(true);
    }

    public function statusUpdate(Request $request)
    {

        $statusData = $request->all();

        $updateStatus = [
            'is_approved' => $statusData['status'],
        ];
        JoeyDocument::where('id', $statusData['id'])->update($updateStatus);
        return 'true';

    }

    /*
        public function show(JoeyDocument $joeyDocumentVerification)
        {

            $joeyDocument=JoeyDocument::where('joey_id',$joeyDocumentVerification->joey_id)->get();
            return view('admin.joeyDocumentVerification.show', compact('joeyDocumentVerification','joeyDocument'));
        }*/
    public function edit(JoeyDocumentVerification $joeys_list)
    {
        $vehiclesList = Vehicle::all();
        $joeyplanList = JoeyPlans::all();
        $hubList = Hubs::all();
        $plan = JoeyPlans::where('id', $joeys_list->plan_id)->first('name');

        /*        $joeyDocument=JoeyDocument::where('joey_id',$joeyDocumentVerification->joey_id)->get();*/
        // dd($joeyDocumentVerification);
        $zone_list = Zone::get();
        return view('admin.joeys.edit', compact('joeys_list', 'vehiclesList', 'joeyplanList', 'plan', 'hubList', 'zone_list'));
    }

    public function update(Request $request, JoeyDocumentVerification $joeys_list)
    {

        // $joey = $this->userRepository->find(auth()->user()->id);
        $this->validate($request, [
            'upload_file' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);
        $data = $request->all();

        $cityData = null;
        if (isset($data['city']))
        {
            $cityData = Cities::where('name',$data['city'])->first();
        }
        $locEnc = Locations::latest()->first();

        $joeyLocation = [
            'address' => isset($data['address']) ? $data['address'] : '',
            'city_id' => isset($cityData) ? $cityData->id : 1088,
            'state_id' => isset($cityData) ? $cityData->state_id : 37,
            'country_id' => isset($cityData) ? $cityData->country_id : 43,
            'postal_code' => isset($data['postalCode']) ? $data['postalCode'] : '',
            'latitude' => isset($data['latitude']) ? $data['latitude'] : '',
            'longitude' => isset($data['longitude']) ? $data['longitude'] : '',
        ];

        $datepicker_start = date("Y-m-d h:i:s");

        $key = 'c9e92bb1ffd642abc4ceef9f4c6b1b3aaae8f5291e4ac127d58f4ae29272d79d903dfdb7c7eb6e487b979001c1658bb0a3e5c09a94d6ae90f7242c1a4cac60663f9cbc36ba4fe4b33e735fb6a23184d32be5cfd9aa5744f68af48cbbce805328bab49c99b708e44598a4efe765d75d7e48370ad1cb8f916e239cbb8ddfdfe3fe';
        $iv ='f13c9f69097a462be81995330c7c68f754f0c6026720c16ad2c1f5f316452ee000ce71d64ed065145afdd99b43c0d632b1703fc6a6754284f5d19b82dc3697d664dc9f66147f374d46c94cf23a78f14f0c6823d1cbaa19c157b4cb81e106b79b11593dcddf675951bc07f54528fc8c03cf66e9c437595d1cac658a737ab1183f';
        $joeyLocationEnc = [
            'address' => isset($data['address']) ? DB::raw("AES_ENCRYPT('".$data['address']."', '".$key."', '".$iv."')") : '',
            'city_id' => isset($cityData) ? $cityData->id : 1088,
            'state_id' => isset($cityData) ? $cityData->state_id : 37,
            'country_id' => isset($cityData) ? $cityData->country_id : 43,
            'postal_code' => isset($data['postalCode']) ? DB::raw("AES_ENCRYPT('".$data['postalCode']."', '".$key."', '".$iv."')") : '',
            'suite' => isset($data['suite']) ? DB::raw("AES_ENCRYPT('".$data['suite']."', '".$key."', '".$iv."')") : '',
            'latitude' => isset($data['latitude']) ? DB::raw("AES_ENCRYPT('".$data['latitude']."', '".$key."', '".$iv."')") : '',
            'longitude' => isset($data['longitude']) ? DB::raw("AES_ENCRYPT('".$data['longitude']."', '".$key."', '".$iv."')") : '',
            'created_at' => $datepicker_start,
            'updated_at' => $datepicker_start,
        ];

        $this->locationRepository->create($joeyLocation);
        $locationEncId = DB::table('locations_enc')-> insertGetId(
            $joeyLocationEnc
        );

        //DB::table('locations_enc')->insert($joeyLocationEnc);


        //$location =$this->locationEncRepository->create($joeyLocationEnc);
        $locEnc = Locations::latest()->first();

        $joeyRecord = [
            'plan_id' => isset($data['plan_id']) ? $data['plan_id'] : NULL,
            //'vehicle_id' => isset($data['vehicle_id']) ? $data['vehicle_id'] : NULL,
            'first_name' => isset($data['first_name']) ? $data['first_name'] : '',
            'last_name' => isset($data['last_name']) ? $data['last_name'] : '',
            'email' => isset($data['email']) ? $data['email'] : '',
            'phone' => isset($data['phone']) ? phoneFormat($data['phone']) : '',
            'hub_id' => isset($data['hub']) ? $data['hub'] : NULL,
            'about' => isset($data['about']) ? $data['about'] : '',
            'address' => isset($data['address']) ? $data['address'] : '',
            'postal_code' => isset($data['postalCode']) ? $data['postalCode'] : NULL,
            'shift_store_type' => isset($data['storeType']) ? $data['storeType'] : '',
            'preferred_zone' => isset($data['prefferedZone']) ? $data['prefferedZone'] : NULL,
            'comdata_cc_num' => isset($data['ComdataCard']) ? $data['ComdataCard'] : NULL,
            'suite' => isset($data['suite']) ? $data['suite'] : NULL,
            'hst_number' => isset($data['HSTNumber']) ? $data['HSTNumber'] : NULL,
            'rbc_deposit_number' => isset($data['RBCDepositCardNumber']) ? $data['RBCDepositCardNumber'] : NULL,
            'location_id' => isset($locationEncId) ? $locationEncId : 1,
        ];

        $is_tax_applied = 0;
        if (isset($data['is_tax_applied'])) {
            $is_tax_applied = 1;
        }
        $joeyRecord['is_tax_applied'] = $is_tax_applied;


        $can_create_order = 0;
        if (isset($data['can_create_order'])) {
            $can_create_order = 1;
        }
        $joeyRecord['can_create_order'] = $can_create_order;


        $is_itinerary = 0;
        if (isset($data['is_itinerary'])) {
            $is_itinerary = 1;
        }
        $joeyRecord['is_itinerary'] = $is_itinerary;


        $has_bag = 0;
        if (isset($data['has_bag'])) {
            $has_bag = 1;
        }
        $joeyRecord['has_bag'] = $has_bag;


        $is_backcheck = 0;
        if (isset($data['is_backcheck'])) {
            $is_backcheck = 1;
        }
        $joeyRecord['is_backcheck'] = $is_backcheck;


        if (!empty($data['upload_file'])) {
            $file = base64_encode(file_get_contents($data['upload_file']));
            $path = $this->upload($file);
            $joeyRecord['image'] = $path;

        }


        $depositRecord = [
            'institution_no' => $data['institutionNo'],
            'branch_no' => $data['branchNo'],
            'account_no' => $data['accountNo'],

        ];
        $vehicle = VehicleDetail::where('joey_id', $joeys_list->id)->first();
        $vehicleRecord = [
            'joey_id' => $joeys_list->id,
            'vehicle_id' => $data['vehicle_id'],
            'license_plate' => $data['license'],
            'color' => $data['color'],
            'model' => $data['model'],
            'make' => $data['make'],
        ];
        if ($vehicle) {
            $vehicle->update($vehicleRecord);
        } else {
            VehicleDetail::create($vehicleRecord);
        }

        $this->joeyDocumentVerificationRepository->update($joeys_list->id, $joeyRecord);
        JoeyDeposit::where('joey_id', $joeys_list->id)->update($depositRecord);
        return redirect()
            ->route('joeys-list.index')
            ->with('success', 'Joey updated successfully.');
    }

    // BELOW fUNCTION FOR IMAGE UPLOAD IN ASSETS
    public function upload($base64Data)
    {

        //dd($base64Data);

        //   $request = new Image_JsonRequest();
        $data = ['image' => $base64Data];

        return $this->sendData('POST', '/', $data)->url;
    }

    public function sendData($method, $uri, $data = [])
    {


        $host = 'assets.joeyco.com';
        //  $host = Config::get('application.api_host');
        // $host ='localhost:8300';

        $json_data = json_encode($data);
        //dd( $data['image']->getClientOriginalName());
        //dd($json_data);
        // $this->reset();


        // if (json_last_error() != JSON_ERROR_NONE) {
        //     throw new \Exception('Bad Request', 400);
        // }

        // $locale = \JoeyCo\Locale::getInstance();

        $headers = [
            'Accept-Encoding: utf-8',
            'Accept: application/json; charset=UTF-8',
            'Content-Type: application/json; charset=UTF-8',
            // 'Accept-Language: ' . $locale->getLangCode(),
            'User-Agent: JoeyCo',
            'Host: ' . $host,
        ];
        // dd($json_data);

        // if (!empty($data) && $method !== 'GET') {

        if (!empty($json_data)) {

            $headers[] = 'Content-Length: ' . strlen($json_data);
            // dd($headers);
        }

        // if (in_array($host, ['api.nightly.joeyco.com', 'api.staging.joeyco.com'])) {

        //     $headers[] = 'Authorization: Basic ' . base64_encode('api:api1243');
        // }

        // $this->signRequest($method, $uri, $headers);
        $url = 'https://' . $host . $uri;
        //   $url = $host . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (strlen($json_data) > 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        // $file=env('APP_ENV');
        //   dd(env('APP_ENV') === 'local');
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


        //dd([$this->originalResponse,$error]);
        curl_close($ch);

        if (empty($error)) {


            $this->response = explode("\n", $this->originalResponse);

            $code = explode(' ', $this->response[0]);
            $code = $code[1];

            $this->response = $this->response[count($this->response) - 1];
            $this->response = json_decode($this->response);

            if (json_last_error() != JSON_ERROR_NONE) {

                $this->response = (object)[
                    'copyright' => 'Copyright © ' . date('Y') . ' JoeyCo Inc. All rights reserved.',
                    'http' => (object)[
                        'code' => 500,
                        'message' => Code::get(500),
                    ],
                    'response' => new stdClass()
                ];
            }
        }

        return $this->response;
    }

    public function documentNotUploaded(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.documentNotUploaded', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function documentNotUploadedData(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereDoesntHave('joeyDocumentsNotUploaded');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->addColumn('check-box', static function ($record) {
                return '<input type="checkbox" class="regular-checkbox" name="multi-select[]"  value="' . $record->id . '">';
            })
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeys.documentNotUploadAction', compact('record'));
            })
            ->addColumn('count', static function ($record) {
                return count($record->joeyNotifications);
            })
            ->rawColumns(['check-box', 'is_active', 'count'])
            ->make(true);
    }

    public function documentNotUploadedNotification(Request $request)
    {
        $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $request->get('id'))->where('key', 'prefs-notification-type')->first();
        $joey_data = JoeyDocumentVerification::where('id', $request->get('id'))->first();

        if ($notifcationType) {
            if ($joey_data) {

                if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                    event(new DocumentNotUploadEvent($joey_data));

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                    ]);
                    if ($notifcationType->value == 'email') {
                        return response()->json(['status' => 200, 'message' => 'Email notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                    if ($joey_data->phone != null) {
                        //dd($joey_data->phone);
                        //set message to send
                        $message = 'You are receiving this notification because document not upload yet';
                        $sid = "ACb414b973404343e8895b05d5be3cc056";
                        $token = "53989b504e630f92e01e5aec2c968d6d";
                        $twilio = new Client($sid, $token);
                        try {

                            $message = $twilio->messages
                                ->create($joey_data->phone, // to
                                    [
                                        "body" => $message,
                                        "from" => "+16477990253"
                                    ]
                                );
                            DB::table('joey_notifications')->insert([
                                ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                            ]);
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            }
                        } catch (Exception $e) {
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }
                        }

                    }
                }

                if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                    $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                    $subject = 'Document Not Upload ';
                    $message = 'Please upload your document';
                    Fcm::sendPush($subject, $message, 'document', null, $deviceIds);
                    $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'document'],
                        'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'document']];
                    $createNotification = [
                        'user_id' => $joey_data->id,
                        'user_type' => 'Joey',
                        'notification' => $subject,
                        'notification_type' => 'document',
                        'notification_data' => json_encode(["body" => $message]),
                        'payload' => json_encode($payload),
                        'is_silent' => 0,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    Notification::create($createNotification);

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                    ]);
                    if ($notifcationType->value == 'alert') {
                        return response()->json(['status' => 200, 'message' => 'Alert notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'all') {
                    return response()->json(['status' => 200, 'message' => 'All notification has been send successfully']);
                }

            }
        }
    }

    public function bulkDocumentNotUploadedNotification(Request $request)
    {
        foreach ($request->get('selected') as $joey) {
            $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $joey)->where('key', 'prefs-notification-type')->first();
            $joey_data = JoeyDocumentVerification::where('id', $joey)->first();
            if ($notifcationType) {
                if ($joey_data) {
                    if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                        event(new DocumentNotUploadEvent($joey_data));
                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                        ]);
                    }
                    if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                        if ($joey_data->phone != null) {
                            //dd($joey_data->phone);
                            //set message to send
                            $message = 'You are receiving this notification because document not upload yet';
                            $sid = "ACb414b973404343e8895b05d5be3cc056";
                            $token = "53989b504e630f92e01e5aec2c968d6d";
                            $twilio = new Client($sid, $token);
                            try {

                                $message = $twilio->messages
                                    ->create($joey_data->phone, // to
                                        [
                                            "body" => $message,
                                            "from" => "+16477990253"
                                        ]
                                    );
                                DB::table('joey_notifications')->insert([
                                    ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                                ]);
                                // return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            } catch (Exception $e) {
                                //return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }

                        }
                    }
                    if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                        $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                        $subject = 'Document Not Upload ';
                        $message = 'Please upload your document';
                        Fcm::sendPush($subject, $message, 'document', null, $deviceIds);
                        $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'document'],
                            'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'document']];
                        $createNotification = [
                            'user_id' => $joey_data->id,
                            'user_type' => 'Joey',
                            'notification' => $subject,
                            'notification_type' => 'document',
                            'notification_data' => json_encode(["body" => $message]),
                            'payload' => json_encode($payload),
                            'is_silent' => 0,
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        Notification::create($createNotification);

                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'not-upload']
                        ]);
                    }
                }
            }
        }
        return response()->json(['status' => 200, 'message' => 'Notification has been to all selected joey successfully']);
    }

    public function documentNotApproved(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.documentNotApproved', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function documentNotApprovedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereHas('joeyDocumentsNotApproved');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeys.attachment', compact('record'));
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function documentApproved(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.documentApproved', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function documentApprovedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereHas('joeyDocumentsApproved');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function notTrained(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.notTrained', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function notTrainedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereDoesntHave('trainingSeen');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->addColumn('check-box', static function ($record) {
                return '<input type="checkbox" class="regular-checkbox" name="multi-select[]"  value="' . $record->id . '">';
            })
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeys.notTrainedAction', compact('record'));
            })
            ->addColumn('count', static function ($record) {
                return count($record->joeyNotifications);
            })
            ->rawColumns(['is_active', 'check-box', 'count'])
            ->make(true);
    }

    public function notTrainedNotification(Request $request)
    {
        $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $request->get('id'))->where('key', 'prefs-notification-type')->first();
        $joey_data = JoeyDocumentVerification::where('id', $request->get('id'))->first();

        if ($notifcationType) {
            if ($joey_data) {

                if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                    event(new NotTrainedEvent($joey_data));

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                    ]);
                    if ($notifcationType->value == 'email') {
                        return response()->json(['status' => 200, 'message' => 'Email notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                    if ($joey_data->phone != null) {
                        //dd($joey_data->phone);
                        //set message to send
                        $message = 'You are receiving this notification because your are not attend training';
                        $sid = "ACb414b973404343e8895b05d5be3cc056";
                        $token = "53989b504e630f92e01e5aec2c968d6d";
                        $twilio = new Client($sid, $token);
                        try {

                            $message = $twilio->messages
                                ->create($joey_data->phone, // to
                                    [
                                        "body" => $message,
                                        "from" => "+16477990253"
                                    ]
                                );
                            DB::table('joey_notifications')->insert([
                                ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                            ]);
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            }
                        } catch (Exception $e) {
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }
                        }

                    }
                }

                if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                    $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                    $subject = 'Training Pending';
                    $message = 'Please attend training';
                    Fcm::sendPush($subject, $message, 'not-trained', null, $deviceIds);
                    $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'not-trained'],
                        'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'not-trained']];
                    $createNotification = [
                        'user_id' => $joey_data->id,
                        'user_type' => 'Joey',
                        'notification' => $subject,
                        'notification_type' => 'not-trained',
                        'notification_data' => json_encode(["body" => $message]),
                        'payload' => json_encode($payload),
                        'is_silent' => 0,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    Notification::create($createNotification);

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                    ]);
                    if ($notifcationType->value == 'alert') {
                        return response()->json(['status' => 200, 'message' => 'Alert notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'all') {
                    return response()->json(['status' => 200, 'message' => 'All notification has been send successfully']);
                }

            }
        }
    }

    public function bulkNotTrainedNotification(Request $request)
    {
        foreach ($request->get('selected') as $joey) {
            $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $joey)->where('key', 'prefs-notification-type')->first();
            $joey_data = JoeyDocumentVerification::where('id', $joey)->first();
            if ($notifcationType) {
                if ($joey_data) {
                    if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                        event(new NotTrainedEvent($joey_data));
                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                        ]);
                    }
                    if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                        if ($joey_data->phone != null) {
                            //dd($joey_data->phone);
                            //set message to send
                            $message = 'You are receiving this notification because your are not attend training';
                            $sid = "ACb414b973404343e8895b05d5be3cc056";
                            $token = "53989b504e630f92e01e5aec2c968d6d";
                            $twilio = new Client($sid, $token);
                            try {

                                $message = $twilio->messages
                                    ->create($joey_data->phone, // to
                                        [
                                            "body" => $message,
                                            "from" => "+16477990253"
                                        ]
                                    );
                                DB::table('joey_notifications')->insert([
                                    ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                                ]);
                                // return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            } catch (Exception $e) {
                                //return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }

                        }
                    }
                    if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                        $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                        $subject = 'Training Pending';
                        $message = 'Please attend training';
                        Fcm::sendPush($subject, $message, 'not-trained', null, $deviceIds);
                        $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'not-trained'],
                            'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'not-trained']];
                        $createNotification = [
                            'user_id' => $joey_data->id,
                            'user_type' => 'Joey',
                            'notification' => $subject,
                            'notification_type' => 'not-trained',
                            'notification_data' => json_encode(["body" => $message]),
                            'payload' => json_encode($payload),
                            'is_silent' => 0,
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        Notification::create($createNotification);

                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'not-trained']
                        ]);
                    }
                }
            }
        }
        return response()->json(['status' => 200, 'message' => 'Notification has been to all selected joey successfully']);
    }

    public function quizPending(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.quizPending', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function quizPendingData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereDoesntHave('joeyPendingQuiz');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->addColumn('check-box', static function ($record) {
                return '<input type="checkbox" class="regular-checkbox" name="multi-select[]"  value="' . $record->id . '">';
            })
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('joeys.quizPendingAction', compact('record'));
            })
            ->addColumn('count', static function ($record) {
                return count($record->joeyNotifications);
            })
            ->rawColumns(['is_active', 'check-box', 'count'])
            ->make(true);
    }

    public function quizPendingNotification(Request $request)
    {
        $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $request->get('id'))->where('key', 'prefs-notification-type')->first();
        $joey_data = JoeyDocumentVerification::where('id', $request->get('id'))->first();

        if ($notifcationType) {
            if ($joey_data) {

                if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                    event(new QuizPendingEvent($joey_data));

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                    ]);
                    if ($notifcationType->value == 'email') {
                        return response()->json(['status' => 200, 'message' => 'Email notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                    if ($joey_data->phone != null) {
                        //dd($joey_data->phone);
                        //set message to send
                        $message = 'You are receiving this notification because your quiz pending';
                        $sid = "ACb414b973404343e8895b05d5be3cc056";
                        $token = "53989b504e630f92e01e5aec2c968d6d";
                        $twilio = new Client($sid, $token);
                        try {

                            $message = $twilio->messages
                                ->create($joey_data->phone, // to
                                    [
                                        "body" => $message,
                                        "from" => "+16477990253"
                                    ]
                                );
                            DB::table('joey_notifications')->insert([
                                ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                            ]);
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            }
                        } catch (Exception $e) {
                            if ($notifcationType->value == 'sms') {
                                return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }
                        }

                    }
                }

                if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                    $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                    $subject = 'Quiz Pending ';
                    $message = 'Please attend quiz';
                    Fcm::sendPush($subject, $message, 'quiz-pending', null, $deviceIds);
                    $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'quiz-pending'],
                        'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'quiz-pending']];
                    $createNotification = [
                        'user_id' => $joey_data->id,
                        'user_type' => 'Joey',
                        'notification' => $subject,
                        'notification_type' => 'quiz-pending',
                        'notification_data' => json_encode(["body" => $message]),
                        'payload' => json_encode($payload),
                        'is_silent' => 0,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    Notification::create($createNotification);

                    DB::table('joey_notifications')->insert([
                        ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                    ]);
                    if ($notifcationType->value == 'alert') {
                        return response()->json(['status' => 200, 'message' => 'Alert notification has been send successfully']);
                    }
                }
                if ($notifcationType->value == 'all') {
                    return response()->json(['status' => 200, 'message' => 'All notification has been send successfully']);
                }

            }
        }
    }

    public function bulkquizPendingNotification(Request $request)
    {
        foreach ($request->get('selected') as $joey) {
            $notifcationType = JoeyMetaData::where('object_type', 'joeys')->where('object_id', $joey)->where('key', 'prefs-notification-type')->first();
            $joey_data = JoeyDocumentVerification::where('id', $joey)->first();
            if ($notifcationType) {
                if ($joey_data) {
                    if ($notifcationType->value == 'email' or $notifcationType->value == 'all') {
                        event(new QuizPendingEvent($joey_data));
                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                        ]);
                    }
                    if ($notifcationType->value == 'sms' or $notifcationType->value == 'all') {
                        if ($joey_data->phone != null) {
                            //dd($joey_data->phone);
                            //set message to send
                            $message = 'You are receiving this notification because your quiz pending ';
                            $sid = "ACb414b973404343e8895b05d5be3cc056";
                            $token = "53989b504e630f92e01e5aec2c968d6d";
                            $twilio = new Client($sid, $token);
                            try {

                                $message = $twilio->messages
                                    ->create($joey_data->phone, // to
                                        [
                                            "body" => $message,
                                            "from" => "+16477990253"
                                        ]
                                    );
                                DB::table('joey_notifications')->insert([
                                    ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                                ]);
                                // return response()->json(['status' => 200, 'message' => 'Sms notification has been send successfully']);
                            } catch (Exception $e) {
                                //return response()->json(['status' => $e->getCode(), 'message' => 'The number ' . $joey_data->phone . ' is not a valid phone number.']);
                            }

                        }
                    }
                    if ($notifcationType->value == 'alert' or $notifcationType->value == 'all') {
                        $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
                        $subject = 'Quiz Pending';
                        $message = 'Please attend quiz';
                        Fcm::sendPush($subject, $message, 'quiz-pending', null, $deviceIds);
                        $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'quiz-pending'],
                            'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'quiz-pending']];
                        $createNotification = [
                            'user_id' => $joey_data->id,
                            'user_type' => 'Joey',
                            'notification' => $subject,
                            'notification_type' => 'quiz-pending',
                            'notification_data' => json_encode(["body" => $message]),
                            'payload' => json_encode($payload),
                            'is_silent' => 0,
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        Notification::create($createNotification);

                        DB::table('joey_notifications')->insert([
                            ['joey_id' => $request->get('id'), 'type' => 'quiz-pending']
                        ]);
                    }
                }
            }
        }
        return response()->json(['status' => 200, 'message' => 'Notification has been to all selected joey successfully']);
    }

    public function quizPassed(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $old_request_data = false;
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.quizPassed', compact('selectjoey', 'joeys', 'old_request_data'));

    }

    public function quizPassedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeyDocumentVerification::where('is_enabled', 1)->whereNull('deleted_at')->whereHas('joeyAttemptedQuiz');
        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }
        if ($request->get('phone')) {
            $query->where('joeys.phone', $request->get('phone'));
        }
        if ($request->get('email')) {
            $query->where('joeys.email', $request->get('email'));
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    /**
     * basic registration
     */
    public function basicRegistration(DataTables $datatables, Request $request)
    {

        $data = $request->all();

        $currentDate = Carbon::now()->format('Y-m-d');
		$currentDate = $currentDate . ' 23:59:59';
        $basicRegistration = JoeyDocumentVerification::where('is_active', 0)->whereNull('deleted_at');

        if ($data['days'] == '3days') {
            $date = Carbon::now()->subDays(3)->format('Y-m-d');
			$date = $date . ' 00:00:00';
            $basicRegistration->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate]);
        } elseif ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
			$startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $basicRegistration->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);
        } elseif ($data['days'] == '15days') {
            $date = Carbon::now()->subDays(15)->format('Y-m-d');
			$date = $date . ' 00:00:00';
            $basicRegistration->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate])->get();
        } elseif ($data['days'] == 'lastmonth') {
            $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
			$firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();
			$lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
            $basicRegistration->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($basicRegistration)

            ->addColumn('action', static function ($basicRegistration) {
                //return redirect(url('joey-document-verification'));
                return '';
            })
        ->make(true);
    }

    /**
     * Doc submissions
     */
    public function docSubmissionTable(DataTables $datatables, Request $request)
    {
        $docomuentSubmission = JoeyDocumentVerification::join('joey_documents', 'joey_documents.joey_id', '=', 'joeys.id')
            ->where('joeys.is_active', 1)
            ->whereNull('joeys.deleted_at')
            ->distinct('joey_documents.joey_id')
            ->whereNull('joey_documents.deleted_at')
            ->select('joeys.id', 'joeys.first_name', 'joeys.address', 'joeys.email', 'joeys.phone');

        $data = $request->all();
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentDate = $currentDate . ' 23:59:59';
        if ($data['days'] == '3days') {

            $date = Carbon::now()->subDays(3)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $docomuentSubmission->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $docomuentSubmission->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);
        } elseif ($data['days'] == '15days') {
            $date = Carbon::now()->subDays(15)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $docomuentSubmission->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);
        } elseif ($data['days'] == 'lastmonth') {

            $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';

            $docomuentSubmission->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($docomuentSubmission)
            ->addColumn('action', static function ($docomuentSubmission) {
                return backend_view('joeys.joey_document_action', compact('docomuentSubmission') );
            })
            ->make(true);
    }

    /**
     * Application submission
     */
    public function totalApplicationSubmissionTable(DataTables $datatables, Request $request)
    {
        $applicationsSubmission = JoeyDocumentVerification::where('is_active', 1)->whereNull('deleted_at');
        $data = $request->all();
        $currentDate = Carbon::now()->format('Y-m-d');

        $currentDate = $currentDate . ' 23:59:59';
        if ($data['days'] == '3days') {

            $date = Carbon::now()->subDays(3)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $applicationsSubmission->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastweek') {

            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');

            $startOfLastWeek=$startOfLastWeek . ' 00:00:00';

            $applicationsSubmission->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);
        }
        elseif ($data['days'] == '15days') {
            $date = Carbon::now()->subDays(15)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $applicationsSubmission->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastmonth') {

            $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

            $firstDayofPreviousMonth =$firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth= $lastDayofPreviousMonth . ' 23:59:59';

            $applicationsSubmission->whereBetween(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($applicationsSubmission)
                 ->addColumn('action', static function ($applicationsSubmission) {
                     return '';
                 })
            ->make(true);
    }

    /**
     * Total training watched
     *
     */
    public function totalTrainingwatchedTable(DataTables $datatables, Request $request)
    {
        $trainingWatched = JoeyDocumentVerification::join('joey_training_seen', 'joey_training_seen.joey_id', '=', 'joeys.id')
            ->where('joeys.is_active', 1)
            ->whereNull('joeys.deleted_at')
            ->distinct('joey_training_seen.joey_id')
            ->select('joeys.id', 'joeys.first_name', 'joeys.address', 'joeys.email', 'joeys.phone');
        $currentDate = Carbon::now()->format('Y-m-d');
        $data = $request->all();
        $currentDate = $currentDate . ' 23:59:59';
        if ($data['days'] == '3days') {

            $date = Carbon::now()->subDays(3)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $trainingWatched->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();

            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';

            $trainingWatched->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);

        } elseif ($data['days'] == '15days') {
            $date = Carbon::now()->subDays(15)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $trainingWatched->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastmonth') {

            $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';

            $trainingWatched->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }

        return $datatables->eloquent($trainingWatched)
            ->addColumn('action', static function ($trainingWatched) {
                return '';
            })
            ->make(true);
    }

    /**
     * Quiz passed Record
     *
     */
    public function totalQuizPassedTable(DataTables $datatables, Request $request)
    {
        $quizPassed = JoeyDocumentVerification::join('joey_attempted_quiz', 'joey_attempted_quiz.joey_id', '=', 'joeys.id')
            ->where('joeys.is_active', 1)
            ->whereNull('joeys.deleted_at')
            ->distinct('joey_attempted_quiz.joey_id')
            ->whereNull('joey_attempted_quiz.deleted_at')
            ->where('joey_attempted_quiz.is_passed', 1)
            ->select('joeys.id', 'joeys.first_name', 'joeys.address', 'joeys.email', 'joeys.phone');
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentDate = $currentDate . ' 23:59:59';
        $data = $request->all();
        if ($data['days'] == '3days') {
            $date = Carbon::now()->subDays(3)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $quizPassed->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $quizPassed->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);

        } elseif ($data['days'] == '15days') {
            $date = Carbon::now()->subDays(15)->format('Y-m-d');
            $date = $date . ' 00:00:00';
            $quizPassed->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$date, $currentDate]);

        } elseif ($data['days'] == 'lastmonth') {

            $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDayofPreviousMonth = Carbon::now()->endOfMonth()->subMonth()->toDateString();

            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';

            $quizPassed->whereBetween(DB::raw("CONVERT_TZ(joeys.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }

        return $datatables->eloquent($quizPassed)
            ->addColumn('action', static function ($quizPassed) {
                return '';
            })
            ->make(true);
    }

    public function agreementNotSigned(Request $request)
    {
        $data = $request->all();
        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $selected_zone = isset($data['zone']) ? $request->get('zone') : '';
        $zones = Zone::whereNull('deleted_at')->get(['id', 'name']);
        // setting old request data
        $old_request_data = $data;

        return view('admin.joeys.agreementNotSigned', compact('selectjoey', 'joeys', 'selected_zone', 'zones', 'old_request_data'));

    }

    public function agreementNotSignedData(DataTables $datatables, Request $request): JsonResponse
    {

        $joeys = JoeyDocumentVerification::pluck('id')->toArray();
        $agreements = AgreementUser::whereNull('signed_at')->pluck('user_id')->toArray();
        $agreements_signed = AgreementUser::whereNotNull('signed_at')->pluck('user_id')->toArray();
        $result = array_diff($joeys, $agreements);
        $joey_id = array_merge($agreements, $result);
        $query = JoeyDocumentVerification::whereIn('id', $joey_id)->whereNotIn('id', $agreements_signed);
        if ($request->start_date && $request->end_date) {
            $query = $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->get('joey')) {
            $query = $query->where('joeys.id', $request->get('joey'));
        }

        if ($request->get('zone')) {
            $query = $query->where('joeys.preferred_zone', $request->get('zone'));
        }


        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('first_name', static function ($record) {
                return $record->first_name . ' ' . $record->last_name;

            })
            ->addColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('preferred_zone', static function ($record) {
                if (isset($record->zone->name)) {
                    return $record->zone->name;
                }
                return '';

            })
            ->editColumn('work_type', static function ($record) {

                if (isset($record->workType->type)) {
                    return $record->workType->type;
                }
                else
                {
                    return $record->work_type;
                }
                //return '';

            })
            ->rawColumns(['is_active'])
            ->make(true);
    }


    /**
     * New Microhub Request Data
     */
    public function newMicrohubRequestData(DataTables $datatables, Request $request)
    {

        $data = $request->all();
//        dd($data);

        $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status', 0)->select('jc_users.*', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status');

        if ($request['radius'] != null) {
            $newHubRequestCount = $newHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->radius);

        }
        if ($request['zoneVal'] != null) {

            $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request['zoneVal']);
        }

        if ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $currentDate = Carbon::now()->format('Y-m-d');


            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $currentDate = $currentDate . ' 23:59:59';

            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);


        } elseif ($data['days'] == 'onemonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(30)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');

            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . '23:59:59';

            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        } elseif ($data['days'] == 'sixmonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(180)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');

            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';

            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);

        }
        return $datatables->eloquent($newHubRequestCount)
            ->make(true);
    }

    /**
     * New Activated Microhub Request Data
     */
    public function newActiveMicrohubData(DataTables $datatables, Request $request)
    {
        $data = $request->all();

        $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('dashboard_users.role_id', 5)
            ->where('micro_hub_request.status', 0)->select('jc_users.*', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status');
        if($request['radius'] != null ) {
            $newHubRequestCount = $newHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->radius);
        }
        if ($request['zoneVal'] != null) {

            $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request['zoneVal']);

        }

        if ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $currentDate = Carbon::now()->format('Y-m-d');

            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $currentDate = $currentDate . ' 23:59:59';

            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);


        } elseif ($data['days'] == 'onemonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(30)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . '23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        } elseif ($data['days'] == 'sixmonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(180)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($newHubRequestCount)
            ->make(true);
    }

    /**
     * New Approved Microhub Request Data
     */
    public function approvedMicrohubData(DataTables $datatables, Request $request)
    {
        $data = $request->all();

        $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('dashboard_users.role_id', 5)
            ->where('micro_hub_request.status', 1)->select('jc_users.*', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status');

        if($request['radius'] != null ) {
            $newHubRequestCount = $newHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->radius);
        }

        if ($request['zoneVal'] != null) {
            $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request['zoneVal']);
        }

        if ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $currentDate = Carbon::now()->format('Y-m-d');
            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $currentDate = $currentDate . ' 23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);

        } elseif ($data['days'] == 'onemonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(30)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . '23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        } elseif ($data['days'] == 'sixmonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(180)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($newHubRequestCount)
            ->make(true);
    }

    /**
     * New Declined Microhub Request Data
     */
    public function declinedMicrohubTableData(DataTables $datatables, Request $request)
    {
        $data = $request->all();
        $newHubRequestCount = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status', 2)->select('jc_users.*', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status');

        if($request['radius'] != null ) {
            $newHubRequestCount = $newHubRequestCount->where('micro_hub_request.area_radius', '<=', $request->radius);
        }

        if ($request['zoneVal'] != null) {
            $newHubRequestCount = $newHubRequestCount->where('jc_users.city', $request['zoneVal']);
        }
        if ($data['days'] == 'lastweek') {
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek = $startOfCurrentWeek->copy()->subDays(7)->format('Y-m-d');
            $currentDate = Carbon::now()->format('Y-m-d');
            $startOfLastWeek = $startOfLastWeek . ' 00:00:00';
            $currentDate = $currentDate . ' 23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$startOfLastWeek, $currentDate]);

        } elseif ($data['days'] == 'onemonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(30)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . '23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        } elseif ($data['days'] == 'sixmonth') {
            $firstDayofPreviousMonth = Carbon::now()->subDays(180)->format('Y-m-d');
            $lastDayofPreviousMonth = Carbon::now()->format('Y-m-d');
            $firstDayofPreviousMonth = $firstDayofPreviousMonth . ' 00:00:00';
            $lastDayofPreviousMonth = $lastDayofPreviousMonth . ' 23:59:59';
            $newHubRequestCount->whereBetween(DB::raw("CONVERT_TZ(jc_users.created_at,'UTC','America/Toronto')"), [$firstDayofPreviousMonth, $lastDayofPreviousMonth]);
        }
        return $datatables->eloquent($newHubRequestCount)
            ->make(true);
    }

}

<?php

namespace App\Http\Controllers\Admin;



use App\Models\JobType;
use App\Models\JoeyChecklist;
use App\Models\OrderCategory;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\Roles;
use App\Models\Training;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\Zone;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    /**
     * Admin Dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $orderCategory = OrderCategory::all()->count();
      //$subAdmin = User::where(['role_id' => User::ROLE_ADMIN])->where(['userType' => 'subadmin'])->get()->count();
        $subAdmin = User::where('id','!=',auth()->user()->id)->where(['userType' => 'admin'])->get()->count();
       // $jobType = JobType::all()->count();
        $zones = Zone::all()->count();
        $worktime = WorkTime::all()->count();
        $worktype = WorkType::all()->count();
        //$training = Training::all()->count();
        $quizByCategory = QuizQuestion::join('order_category','order_category.id','=','quiz_questions.order_category_id')->whereNull('order_category.deleted_at')->get()->count();

        //$quizByVendor = QuizQuestion::whereNotNull('vendor_id')->get()->count();
        $roles= Roles::NotAdminRole()->count();
        $trainingBycategory=Training::whereNotNull('order_category_id')->get()->count();
        $trainingByVendor=Training::whereNotNull('vendors_id')->get()->count();
        $joeyCheckList= JoeyChecklist::all()->count();

      return view('admin.dashboard.index',compact('subAdmin','zones','worktime','worktype','quizByCategory',
      'roles','trainingBycategory','trainingByVendor','joeyCheckList'));
       // return view('admin.dashboard.index');

    }
}
;

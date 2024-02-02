<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreQuizManagementRequest;
use App\Http\Requests\Admin\UpdateQuizManagementRequest;
use App\Models\JoeyAttemptQuiz;
use App\Models\JoeyQuiz;
use App\Models\MicroHubAttemptQuiz;
use App\Models\MicroHubQuiz;
use App\Models\OrderCategory;
use App\Models\QuizQuestion;
use App\Models\Vendor;
use App\Repositories\Interfaces\QuizAnswerRepositoryInterface;
use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MicroHubAttemptQuizController extends Controller
{
    private $quizQuestionRepository;

    public function __construct(QuizQuestionRepositoryInterface $quizQuestionRepository)
    {
        parent::__construct();
        $this->quizQuestionRepository = $quizQuestionRepository;
        /*  $this->quizAnswerRepository = $quizAnswerRepository;*/
    }


    public function index()
    {
        return view('admin.micro-hub.attempt-quiz.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = MicroHubQuiz::with('category')->select('microhub_attempted_quiz.*');
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('category', static function ($record) {

                if ($record->category) {
                    return $record->category->name;
                }
                return '';
            })
            ->addColumn('passed', static function ($record) {

                $return = 'NO';
                if ($record->is_passed != '0') {
                    $return = 'YES';
                }
                return $return;
            })
            ->addColumn('micro_hub_user', static function ($record) {

                $return = '';
                if (isset($record->microHubUsers)) {
                    $return = $record->microHubUsers->full_name;
                }
                return $return;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.attempt-quiz.action', compact('record'));
            })
            ->rawColumns(['quiz', 'category'])
            ->make(true);
    }

    public function show($attemptQuiz)
    {
        $details = MicroHubAttemptQuiz::where('quiz_id', $attemptQuiz)->get();
        return view('admin.micro-hub.attempt-quiz.show', compact('attemptQuiz', 'details'));
    }
}

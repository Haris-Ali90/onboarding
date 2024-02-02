<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreQuizManagementRequest;
use App\Http\Requests\Admin\UpdateQuizManagementRequest;
use App\Models\JoeyAttemptQuiz;
use App\Models\JoeyQuiz;
use App\Models\OrderCategory;
use App\Models\QuizQuestion;
use App\Models\Vendor;
use App\Repositories\Interfaces\QuizAnswerRepositoryInterface;
use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JoeyAttemptQuizController extends Controller
{
    private $quizQuestionRepository;
/*    private $quizAnswerRepository;*/

    /**
     * Create a new controller instance.
     *
     * @param QuizQuestionRepositoryInterface $quizQuestionRepository
     * @param QuizAnswerRepositoryInterface $quizAnswerRepository
     */


    public function __construct(QuizQuestionRepositoryInterface $quizQuestionRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->quizQuestionRepository = $quizQuestionRepository;
      /*  $this->quizAnswerRepository = $quizAnswerRepository;*/
    }


    public function index()
    {
        return view('admin.joey-attempt-quiz.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeyQuiz::with('category')->select('joey_attempted_quiz.*');
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

                $return ='NO';
                         if ($record->is_passed !='0') {
                    $return = 'YES';
                }
                return $return;
            })
            ->addColumn('joey', static function ($record) {

                $return ='';
                if (isset($record->joey)) {
                    $return = $record->joey->first_name.' '.$record->joey->last_name;
                }
                return $return;
            })

            ->addColumn('action', static function ($record) {
                return backend_view('joey-attempt-quiz.action', compact('record'));
            })
            ->rawColumns(['quiz','category'])
            ->make(true);
    }


/*    public function create()
    {
        $data['order_categories'] = OrderCategory::all();
       // $data['vendors'] = Vendor::all();
        return view('admin.quizManagement.create', $data);
    }*/


///*    public function store(StoreQuizManagementRequest $request)
//    {
//        $data = $request->all();
//        $correctAnswer = null;
//
//        if (isset($data['right'])) {
//            $correctAnswer = $data['right'];
//        }
//
//        $order_category_id = $data['order_category_id'];
//      /*  $vendor_id = $data['vendor_id'];*/
//
//      //  if ($data['type'] == 'orderCategoryDD') {
//            $type = 'order_category_id';
//        //    $vendor_id = null;
//       // } else {
//        //    $order_category_id = null;
//       //     $type = 'vendor_id';
//      //  }
//        $lastQuiz = QuizQuestion::latest('id')->first();
//        if (is_null($lastQuiz)) {
//            $form_id = 1;
//        } else {
//
//            $form_id = $lastQuiz->form_id + 1;
//        }
//
//        $insert = [
//            'order_category_id' => $order_category_id,
//            'vendor_id' => null,
//            'question' => $data['question'],
//            'form_id' => $form_id,
//
//        ];
//
//        $questionId = $this->quizQuestionRepository->create($insert);
//
//        $insertAns = [];
//        foreach ($data['ans'] as $ans) {
//            $insertAns[] = [
//                'quiz_questions_id' => $questionId->id,
//                'answer' => $ans,
//            ];
//        }
//
//        $this->quizAnswerRepository->insert($insertAns);
//
//        // $query123  = QuizQuestion::with('answers')->find($questionId->id);
//        $ans_ids = $questionId->answers()->pluck('id')->toarray();
//
//        if ($correctAnswer) {
//            $correctAnswerId = $ans_ids[$correctAnswer - 1];
//            $insertCorrectAnswerId = [
//                'correct_answer_id' => $correctAnswerId,
//            ];
//            $this->quizQuestionRepository->update($questionId->id, $insertCorrectAnswerId);
//        }
//        return redirect()
//            ->route('quiz-management.index')
//            ->with('success', 'Quiz added successfully.');
//    }*/




    public function show(JoeyQuiz $joeyAttemptQuiz)
    {

     $details=JoeyAttemptQuiz::where('quiz_id',$joeyAttemptQuiz->id)->get();

             return view('admin.joey-attempt-quiz.show', compact('joeyAttemptQuiz','details'));
    }


//    public function edit(QuizQuestion $quizManagement)
//    {
//        //dd($quizManagement);
//        $dataQuizQuestion = QuizQuestion::all();
//        $dataQuizAnswer = $quizManagement->answers()->select('id', 'answer')->get();
//        $Ordercategories = OrderCategory::all();
//
//        return view('admin.quizManagement.edit', compact('quizManagement', 'dataQuizAnswer','Ordercategories'));
//    }
//
//
//    public function update(UpdateQuizManagementRequest $request, QuizQuestion $quizManagement)
//    {
//
//        $question = $request->all();
//
//        $quizQuestion = [
//            'question' => $question['question'],
//            'order_category_id' => $question['category_id'],
//            'correct_answer_id' => $question['right'],
//        ];
//        $this->quizQuestionRepository->update($quizManagement->id, $quizQuestion);
//        $i = 0;
//
//        foreach ($question['ans'] as $ans) {
//            $this->quizAnswerRepository->update($question['old-id'][$i], ['answer' => $ans]);
//            $i++;
//        }
//
//
//        return redirect()
//            ->route('quiz-management.index')
//            ->with('success', 'Quiz updated successfully.');
//    }
//
//    public function destroy(QuizQuestion $quizManagement)
//    {
//        $quizManagement->answers()->select('quiz_questions_id')->delete();
//
//        $quizManagement->delete();
//
//        return redirect()
//            ->route('quiz-management.index')
//            ->with('success', 'Quiz has removed successfully.');
//    }

}

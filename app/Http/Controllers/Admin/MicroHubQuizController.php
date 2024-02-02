<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\StoreQuizManagementRequest;
use App\Http\Requests\Admin\UpdateQuizManagementRequest;
use App\Models\OrderCategory;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Repositories\Interfaces\QuizAnswerRepositoryInterface;
use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MicroHubQuizController extends Controller
{
    private $quizQuestionRepository;
    private $quizAnswerRepository;

    /**
     * Create a new controller instance.
     *
     * @param QuizQuestionRepositoryInterface $quizQuestionRepository
     * @param QuizAnswerRepositoryInterface $quizAnswerRepository
     */
    public function __construct(QuizQuestionRepositoryInterface $quizQuestionRepository, QuizAnswerRepositoryInterface $quizAnswerRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->quizQuestionRepository = $quizQuestionRepository;
        $this->quizAnswerRepository = $quizAnswerRepository;
    }

    public function index()
    {
        return view('admin.micro-hub.quizManagement.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = OrderCategory::where('user_type','micro_hub');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })

            ->addColumn('question_count', static function ($record) {
                return $record->questions->count();
            })
            ->addColumn('action', static function ($record) {

                return backend_view('micro-hub.quizManagement.action', compact('record'));
            })
            ->rawColumns(['answers', 'is_active'])
            ->make(true);
    }

    public function create()
    {
        $data['order_categories'] = OrderCategory::where('user_type','micro_hub')->get();
       // $data['vendors'] = Vendor::all();
        return view('admin.micro-hub.quizManagement.create', $data);
    }

    public function store(StoreQuizManagementRequest $request)
    {
        $data = $request->all();


        $correctAnswer = null;

        if (isset($data['right'])) {
            $correctAnswer = $data['right'];
        }

        $order_category_id = $data['order_category_id'];

        $lastQuiz = QuizQuestion::latest('id')->first();
        if (is_null($lastQuiz)) {
            $form_id = 1;
        } else {

            $form_id = $lastQuiz->form_id + 1;
        }

        $insert = [
            'order_category_id' => $order_category_id,
            'vendor_id' => null,
            'question' => $data['question'],
            'form_id' => $form_id,
            'user_type' => 'micro_hub',

        ];

        if ($request->hasfile('questionImage')) {
            $file = $request->file('questionImage');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $insert['image'] = url(backendUserFile() . $file->getClientOriginalName());

        }


       $questionId = $this->quizQuestionRepository->create($insert);

        $insertAns = [];
        foreach ($data['ans'] as $key => $ans) {
            $image_path = '';
            if(!empty($data['image'])){
                $image_data = $data['image'];

                //checking key exist for image
                if(array_key_exists($key,$image_data))
                {
                    $file = $image_data[$key];
                    $fileName = date('Ymdhis').'-'.uniqid().'-'.$file->getClientOriginalName();
                    $file->move(backendUserFile(), $fileName);
                    $image_path = url(backendUserFile() . $file->getClientOriginalName());
                }


            }

            $insertAns[] = [
                'quiz_questions_id' => $questionId->id,
                'answer' => $ans,
                'image' =>$image_path
            ];

        }

//        dd($data,$insertAns);
        $answerId = $this->quizAnswerRepository->insert($insertAns);

        // $query123  = QuizQuestion::with('answers')->find($questionId->id);
        $ans_ids = $questionId->answers()->pluck('id')->toarray();

        if ($correctAnswer) {
            $correctAnswerId = $ans_ids[$correctAnswer - 1];
            $insertCorrectAnswerId = [
                'correct_answer_id' => $correctAnswerId,
            ];
            $this->quizQuestionRepository->update($questionId->id, $insertCorrectAnswerId);
        }


        return redirect()
            ->route('quiz-management-list.index')
            ->with('success', 'Quiz added successfully.');
    }

    public function show($quizManagement)
    {
        $quizManagement = OrderCategory::find($quizManagement);
        return view('admin.micro-hub.quizManagement.show', compact('quizManagement'));
    }

    public function edit($quizManagement)
    {

        $quizManagement = QuizQuestion::find($quizManagement);
        $dataQuizQuestion = QuizQuestion::all();
        $dataQuizAnswer = $quizManagement->answers()->select('id', 'answer','image')->get();

        $Ordercategories = OrderCategory::where('user_type','micro_hub')->get();
        return view('admin.micro-hub.quizManagement.edit', compact('quizManagement', 'dataQuizAnswer','Ordercategories'));
    }

    public function update(UpdateQuizManagementRequest $request, $quizManagement)
    {

        $question = $request->all();

        $quizQuestion = [
            'question' => $question['question'],
            'order_category_id' => $question['category_id'],
            'correct_answer_id' => $question['right'],
        ];
        if ($request->hasfile('questionImage')) {
            $file = $request->file('questionImage');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $quizQuestion['image'] = url(backendUserFile() . $file->getClientOriginalName());

        }

        $this->quizQuestionRepository->update($quizManagement, $quizQuestion);
        $i = 0;

        foreach ($question['ans'] as$key =>$ans) {


            $oldAnswerRecord=QuizAnswer::where('id',$question['old-id'][$i])->first();

            $image_data = (isset($question['image'])) ? $question['image']: $question['image'] = [] ;
            $image_path =$oldAnswerRecord->image;
            if(array_key_exists($key,$image_data))
            {
                $file = $image_data[$key];
                $fileName = date('Ymdhis').'-'.uniqid().'-'.$file->getClientOriginalName();
                $file->move(backendUserFile(), $fileName);
                $image_path = url(backendUserFile() . $file->getClientOriginalName());
            }
            $this->quizAnswerRepository->update($question['old-id'][$i], ['answer' => $ans,'image' =>$image_path]);
            $i++;
        }

      return redirect()
            ->route('quiz-management-list.index')
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy($quizManagement)
    {
        $quizManagement = QuizQuestion::find($quizManagement);
        $quizManagement->answers()->select('quiz_questions_id')->delete();

        $quizManagement->delete();

        return redirect()
            ->route('quiz-management-list.index')
            ->with('success', 'Quiz has removed successfully.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\StageAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Region;
use App\Models\Point;
use Illuminate\Support\Facades\App;

class QuizController extends Controller
{
    public function storeQuiz(Request $request) {
        $data = $request->json()->all();
 if ($data['type'] === 'normal') {
     // Create a new quiz
     $quiz = new Quiz();
     $quiz->title = $data['title'];
     $quiz->save();

     foreach ($data['question'] as $questionData) {
         $question = new Question();
         $question->title = $questionData['title'];
         $question->img_src = $questionData['img_src'];
         $question->img_name = $questionData['img_name'];
         $question->top = $questionData['top'];
         $question->left = $questionData['left'];
         $question->score = $questionData['score'];

         $quiz->questions()->save($question);

         if (isset($questionData['answers']) && is_array($questionData['answers'])) {
             foreach ($questionData['answers'] as $answerData) {
                 $answer = new Answer();
                 $answer->title = $answerData['title'];
                 $answer->answer_id = $answerData['answerId'];
                 $answer->status = $answerData['status'];
                 $question->answers()->save($answer);

                 // Create a new region for each answer
                 $region = new Region();
                 $answer->region()->save($region);

                 if (isset($answerData['region']) && is_array($answerData['region'])) {
                     foreach ($answerData['region'] as $regionData) {
                         $point = new Point();
                         $point->x = $regionData['x'];
                         $point->y = $regionData['y'];
                         $region->points()->save($point);
                     }
                 }
             }
         }
     }

     return response()->json(['data' => $quiz->load('questions.answers.region.points')], 200);
 }
        if ($data['type'] === 'sequence') {
            $quiz = new Quiz();
            $quiz->title = $data['quizTitle'];
            $quiz->save();

                $question = new Question();
                $question->title = $data['questionTitle'];
                $question->img_src = '';
                $question->img_name = '';
                $question->top = 0;
                $question->left = 0;
                $question->score = 100;

                $quiz->questions()->save($question);
            if (isset($data['stages']) && is_array($data['stages'])) {
                    foreach ($data['stages'] as $stagesData) {
                        $stage = new Stage();
                        $stage->stage_num = $stagesData['questionId'];
                        $stage->question_id = $question->id;
                        $stage->img_src = $stagesData['image'];
                        $stage->img_name = $stagesData['image'];
                        $question->stages()->save($stage);
                        if (isset($stagesData['answers']) && is_array($stagesData['answers'])) {
                            foreach ($stagesData['answers'] as $answerData) {
                                $answer = new Answer();
                                $answer->title = $answerData['label'];
                                $answer->answer_id = $answerData['answerId'];
                                $answer->status = $answerData['status'];
                                $answer->question_id = $question->id;
                                $answer->save();
                                $region = new Region();
                                $region->save();
                                if (isset($answerData['points']) && is_array($answerData['points'])) {
                                    foreach ($answerData['points'] as $regionData) {
                                        $point = new Point();
                                        $point->x = $regionData['x'];
                                        $point->y = $regionData['y'];
                                        $region->points()->save($point);
                                    }
                                }
//                                $stage->save();
                                $rel = new StageAnswer([
                                   'region_id'  =>  $region->id,
                                   'answer_id'  =>  $answer->id,
                                   'stage_id'   =>  $stage->id,
                                ]);
                                $rel->save();
                            }

                        }
                        }
                    }

            return response()->json(['data' => $quiz->load('questions.stages.answers.regions.points')], 200);
        }
    }



    public function getAllQuizzes()
    {
        $quizzes = Quiz::with('questions.stages.answers.regions.points', 'questions.answers.region.points')->get();
        if ($quizzes) {
            return response()->json($quizzes, 200);
        } else {
            return response()->json(['message' => 'User Not Found'], 200);
        }
    }

    public function deleteQuiz($id)
    {
        $quizzes = Quiz::with('questions.answers.region.points')->get();
        $QuizData = $quizzes->find($id);
//        return $QuizData;
        if ($QuizData) {
                 foreach ($QuizData->questions as $question) {
                     $questionData = Question::find($question->id);
                     if ($questionData->answers) {
                         foreach ($questionData->answers as $answer){
                             $answerData = Answer::find($answer->id);
                             if ($answerData->region) {
                                 if($answerData->region->points){
                                     foreach ($answerData->region->points as $point){
                                         Point::find($point->id)->delete();
                                     }
                                 }
                                 Region::find($answerData->region->id)->delete();
                             }
                             Answer::find($answerData->id)->delete();
                         }
                         Question::find($questionData->id)->delete();
                     }
                 }
            Quiz::find($QuizData->id)->delete();
            return response()->json(['message' =>  "Quiz $QuizData->title has been successfully deleted", "Quiz" => $QuizData], 200);
        } else {
            // Authentication failed
            return response()->json(['Error' => "Delete Quiz $QuizData->title Failed", 'message' => 'Quiz Not Found'], 200);
        }
    }

    public function deleteQuizzes(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];

        $deletedQuizzes = [];

        foreach ($ids as $id) {
            $quizData = Quiz::find($id);
            if ($quizData) {
                $this->deleteQuiz($quizData);
                $deletedQuizzes[] = $quizData->title;
            }
        }

        if (!empty($deletedQuizzes)) {
            return response()->json(['message' => 'Quizzes deleted successfully', 'deleted_quizzes' => $deletedQuizzes], 200);
        } else {
            return response()->json(['error' => 'Delete Failed', 'message' => 'No Quizzes found for the provided IDs'], 200);
        }
    }

}

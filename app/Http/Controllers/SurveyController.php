<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseWrapper;
use App\Utils\Calculator;
use App\Models\Course;
use App\Models\Survey;
use App\Models\Form;
use App\Models\Question;
use App\Models\Dosurvey;

class SurveyController extends Controller
{
    public function showSurveyForm(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('do-survey'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);


        $courseid =  $request->get('courseid');
        $id = Survey::where('course_id', $courseid)->first()->id;

        if (Dosurvey::where([
            ['student_id', $user->id],
            ['survey_id', $id]
        ]) -> exists()) {
            return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'this survey has done'), 400);
        }

        $name = Survey::where('course_id', $courseid)->first()->name;
        $form = Survey::where('course_id', $courseid)->first()->form()->first();
        $question = $form->questions(); 
        $data = [];
        foreach ($question->distinct('category')->pluck('category')->toArray() as $cat) {
            array_push($data, [
                'category' => $cat,
                'questions' => Question::where('category', $cat)->get(['id', 'content'])->toArray()
            ]);
        }
        $data = [
            'survey_id' => $id,
            'name' => $name,
            'form' => $data
        ];
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }

    public function doSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('do-survey'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        /* need check */
        $survey_id = $request->get('survey_id');

        if (Dosurvey::where([
            ['student_id', $user->id],
            ['survey_id', $survey_id]
        ]) -> exists()) {
            return response()->json(ResponseWrapper::wrap(false, 400, 'reason', 'this survey has done'), 400);
        }

        $ans_arr = $request->get('answers');
        foreach ($ans_arr as $question_id => $ans) {
            $dosurvey = new Dosurvey([
                'student_id' => $user->id,
                'survey_id' => $survey_id,
                'question_id' => $question_id,
                'answer' => $ans % 6
            ]);
            $dosurvey->save();
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }

    public function showSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if ($user->hasPermission('survey-management'))
            $surveys = Survey::get();
        else if ($user->hasPermission('survey-result')) {
            $course_id = $user->courses()->pluck('id')->toArray();
            $surveys = Survey::findMany($course_id)->all();
        } else
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $data = [];
        foreach ($surveys as $record) {
            $survey_info = [
                'id' => $record->id,
                'name' => $record->name,
                'course_code' => $record->course()->first()->course_code,
                'course_name' => $record->course()->first()->name,
                'form_name' => $record->form()->first()->name,
                'teacher_name' => $record->course()->first()->teacher()->first()->name,
                'create_at' => $record->create_at,
            ];
            array_push($data, $survey_info);
        }
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));

    }

    public function addSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('survey-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        /* need check */
        $in = array_values($request->only('name', 'course_id', 'form_id'));
        $survey = new Survey([
            'name' => $in[0],
            'course_id' => $in[1],
            'form_id' => $in[2]
        ]);
        $survey->save();
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
                'id' => $survey->id,
                'name' => $survey->name,
                'course_code' => $survey->course()->first()->course_code,
                'course_name' => $survey->course()->first()->name,
                'form_name' => $survey->form()->first()->name,
                'teacher_name' => $survey->course()->first()->teacher()->first()->name,
                'create_at' => $survey->create_at,
        ]));
    }

    public function editSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('survey-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $in = array_values($request->only('id', 'name', 'course_id', 'form_id'));
        $survey = Survey::find($in[0]);
        $survey->update([
            'name' => $in[1],
            'course_id' => $in[2],
            'form_id' => $in[3]
        ]);
        $survey->save();
        
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', [
                'id' => $survey->id,
                'name' => $survey->name,
                'course_code' => $survey->course()->first()->course_code,
                'course_name' => $survey->course()->first()->name,
                'form_name' => $survey->form()->first()->name,
                'teacher_name' => $survey->course()->first()->teacher()->first()->name,
                'create_at' => $survey->create_at,
        ]));
    }

    public function deleteSurvey(Request $request) {
        foreach (['admin', 'student', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        if (! $user->hasPermission('survey-management'))
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $survey_id = $request->get('id');
        Survey::find($survey_id)->delete();

        return response()->json(ResponseWrapper::wrap(true, 200, 'data', []));
    }

    public function showResult(Request $request) {
        foreach (['admin', 'teacher'] as $guard) {
            $user = $request->user($guard);
            if ($user)
                break;
        }
        if (! $user)
            return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);

        $surveyid = $request->get('id');
        $survey = Survey::find($surveyid);
        if (! $user->hasPermission('survey-management')) {
            if (! $user->courses()->where('id', $survey->course_id)-> exists())
                return response()->json(ResponseWrapper::wrap(false, 401, 'reason', 'permission denied'), 401);
        }

        $question = $survey->form()->first()->questions()->get(['question.id', 'content']);
        $result = [];
        foreach ($question as $q) {
            $answer_res = Dosurvey::where([
                ['question_id', $q->id],
                ['survey_id', $surveyid]
            ]);
            $m = $answer_res->avg('answer');
            $std = 0;
            /* $m1 = 0; */
            /* $std1 = 0; */
            /* $m2 = 0; */
            /* $std2 = 0; */
            array_push($result, [
                'question_id' => $q->id,
                'question_content' => $q->content,
                'M' => $m,
                'STD' => $std,
                /* 'M1' => $m1, */
                /* 'STD1' => $std1, */
                /* 'M2' => $m2, */
                /* 'STD2' => $std2, */
            ]);
        }
        $data = [
            'survey_id' => $surveyid,
            'name' => $survey->name,
            'course_code' => $survey->course()->first()->course_code,
            'course_name' => $survey->course()->first()->name,
            'form_name' => $survey->form()->first()->name,
            'result' => $result,
        ];
        return response()->json(ResponseWrapper::wrap(true, 200, 'data', $data));
    }
}

/*
 *function phuongSai(data, id) {
          var phuongSai;
          var sumPhuongSai = 0;
          var avg = avgPoint(data, id);


          for (var i = 0; i < data.length; i++) {
            var array = JSON.parse(data[i].result);
            //console.log(array);
            sumPhuongSai += Math.pow(array.r[id] - avg, 2);

          }

          console.log(sumPhuongSai/data.length);

          if (isNaN(Math.sqrt(sumPhuongSai/data.length))) {
            return 0;
          }

          return Math.sqrt(sumPhuongSai/data.length);
      }
function avgPoint(data, id) { // tính điểm trung bình cho từng câu hỏi theo mã
        var sum = 0;
        for (var i = 0; i < data.length; i++) {
          var array = JSON.parse(data[i].result);
          //console.log(array);
          sum += parseInt(array.r[id]);
        }
        if (isNaN(sum/data.length)) {
          return 0;
        }
        return sum/data.length;
      }
 */

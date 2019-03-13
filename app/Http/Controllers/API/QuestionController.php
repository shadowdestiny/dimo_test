<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    //TODO: Improvement action
    public function question($uuid)
    {
        $question = Question::find($uuid);

        return response()->json($question, 200);
    }

    //TODO: Improvement action
    public function questionBySteep($steep_uuid)
    {
        $questions = Question::join('steps', 'steps.uuid', '=', 'questions.step_uuid')
        ->where('steps.uuid', '=', $steep_uuid)
            ->select('questions.*')
            ->orderBy('questions.created_at', 'desc')
            ->get();

        return response()->json($questions, 200);
    }

    //TODO: Improvement action
    public function add(Request $request)
    {
        $question = new Question();
        $question->fill($request->all());
        $question->save();

        return response()->json($question, 200);
    }

    //TODO: Improvement action
    public function update(Request $request)
    {
        $question = Question::find($request->uuid);
        $question->fill($request->all());
        $question->save();

        return response()->json(['successful' => 'Ok'], 200);
    }

    //TODO: Improvement action
    public function drop(Request $request)
    {
        $question = Question::find($request->uuid);
        $question->delete();

        return response()->json(['successful' => 'Ok'], 200);
    }
}

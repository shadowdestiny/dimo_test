<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\ClientInfo;
use App\Http\Controllers\Controller;
use App\Http\Resources\StepResource;
use App\Step;
use Illuminate\Http\Request;

class StepsController extends Controller
{
    //TODO: Improvement action
    public function add(Request $request)
    {
        $question = new Step();
        $question->fill($request->all());
        $question->save();

        return response()->json($question, 200);
    }

    //TODO: Improvement action
    public function drop(Request $request)
    {
        $question = Step::find($request->uuid);
        $question->delete();

        return response()->json(['successful' => 'Ok'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Step $step
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Step $step)
    {
        return new StepResource($step);
    }

    /**
     * Update the specified resource in storage.
     * TODO: Improvement action.
     *
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $question = Step::find($request->uuid);
        $question->fill($request->all());
        $question->save();

        return response()->json(['successful' => 'Ok'], 200);
    }

    public function getClient($client_uuid){
        $client = ClientInfo::where('client_uuid','=',$client_uuid)->first();
        return response()->json($client, 200);
    }

    //TODO: Improvement action
    public function steps($client_uuid)
    {
        $steps = Step::join('questions', 'questions.step_uuid', '=', 'steps.uuid')
        ->leftJoin('answers', 'answers.question_uuid', '=', 'questions.uuid')
        ->where('answers.client_uuid', $client_uuid)
        ->select(['steps.name', 'steps.uuid', 'steps.order as steps_order',
        'questions.uuid as questions_uuid',
        'questions.text',
        'questions.order as questions_order',
        'answers.response as response',
        ])
        ->get();

        return response()->json($steps, 200);
    }

    //TODO: Improvement action
    public function stepsList()
    {
        $steps = Step::orderBy('steps.order', 'asc')->get();

        return response()->json($steps, 200);
    }
}

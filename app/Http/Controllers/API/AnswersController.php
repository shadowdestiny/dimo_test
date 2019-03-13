<?php

namespace App\Http\Controllers\API;

use App\Answer;
use App\Client;
use App\ClientInfo;
use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    /**
     * Question instance.
     *
     * @var \App\Question
     */
    protected $questions;

    /**
     * Client instance.
     *
     * @var \App\Client
     */
    protected $client;

    /**
     * Answer instance.
     *
     * @var \App\Answer
     */
    protected $answers;

    /**
     * Create controller instance.
     *
     * @param \App\Question $questions
     */
    public function __construct(Question $questions, Client $client, Answer $answers)
    {
        $this->questions = $questions;
        $this->client    = $client;
        $this->answers   = $answers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $client_uuid = $request->get('client_uuid');
        $data        = collect($request->get('answers'));

        $data->each(function ($item, $key) use ($client_uuid) {
            $question = $this->questions->whereUuid($item['uuid'])->firstOrFail();
            $answer = new Answer();
            $answer->question_uuid = $item['uuid'];
            $answer->client_uuid = $client_uuid;
            $answer->fill($item);
            $question->answers()->save($answer);
        });

        if (true == $request->get('complete')) {
            $client         = $this->client->whereUuid($client_uuid)->first();
            $client->status = Client::AVAILABLE;
            $client->update();
            $this->info($client);
        }

        return response()->json(['message' => 'The answers were saved.']);
    }

    /**
     * Update answers.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = collect($request->get('answers'));

        $data->each(function ($item, $key) {
            $answer = $this->answers->whereUuid($item['uuid'])->firstOrFail();
            $answer->response = $item['response'];
            $answer->update();
        });

        return response()->json(['message' => 'The answers were updated.']);
    }

    public function info($client)
    {
        $info                           = new ClientInfo();
        $info->first_name               = $this->getAnswer($client, 'Nombres');
        $info->last_name                = $this->getAnswer($client, 'Apellidos');
        $info->birth_date               = $this->getAnswer($client, 'Fecha de Nacimiento');
        $info->gender                   = 'Hombre' == $this->getAnswer($client, 'Genero') ? 'male' : 'female';
        $info->dui                      = $this->getAnswer($client, 'Número de DUI');
        $info->address                  = $this->getAnswer($client, 'Dirección');
        $info->city_id                  = $this->getAnswer($client, 'Departamento');
        $info->email                    = $this->getAnswer($client, 'Correo Electrónico');
        $info->alternative_number_phone = $this->getAnswer($client, 'Por Favor ingresa un número alterno en caso no podamos contactarte al teléfono registrado');
        $info->client_uuid              = $client->uuid;
        $info->save();
    }

    public function getAnswer($client, $value)
    {
        return $client->answers->where('question_uuid', Question::whereText($value)->value('uuid'))->first()->response;
    }
}

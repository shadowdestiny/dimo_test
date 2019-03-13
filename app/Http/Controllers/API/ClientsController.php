<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\ClientData;
use App\ClientInfo;
use App\ClientWallet;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientDashboardResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientWalletResource;
use App\Http\Resources\LoanResource;
use App\Loan;
use App\Setting;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Client Info instance.
     *
     * @var \App\ClientInfo
     */
    protected $info;

    /**
     * Client Wallet instance.
     *
     * @var \App\ClientWallet
     */
    protected $wallets;

    /**
     * Create instance controller.
     *
     * @param \App\ClientInfo   $info
     * @param \App\ClientWallet $wallets
     */
    public function __construct(ClientInfo $info, ClientWallet $wallets)
    {
        $this->info    = $info;
        $this->wallets = $wallets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreClientRequest $request
     * @param \App\Client                           $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $request, Client $client)
    {
        $client_info = $this->info->fill($request->all());

        if ($client->info()->save($client_info)) {
            return new ClientResource($client);
        }

        return response()->json(['error' => 'The given data was invalid.'], 400);
    }

    //TODO: Improvement action
    public function record(Request $request)
    {
        $data   = $request->all();
        $client = Client::find($request->uuid);
        $client->fill($data);
        $client->save();
        if ('approved' == $request->get('status')) {
            $loan = Loan::where('client_uuid', $client->uuid)->where('status', Loan::PENDING)->first();
            $this->pushNotification($client->token, $loan->amount->amount);
        }

        return response()->json(['successful' => 'Ok'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Client              $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Client $client)
    {
        $info = $this->info->whereClientUuid($client->uuid)->first();

        if ($info->update($request->all())) {
            return new ClientResource($client);
        }

        return response()->json(['error' => 'The given data was invalid.'], 400);
    }

    /**
     * Get loans belongs to client.
     *
     * @param \App\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loans(Client $client)
    {
        $loans = $client->loans()->get();

        return LoanResource::collection($loans);
    }

    /**
     * Get loans belongs to client.
     *
     * @param \App\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wallets(Client $client)
    {
        $wallets = $client->wallets()->get();

        return ClientWalletResource::collection($wallets);
    }

    //TODO: Improvement action
    public function clients($type, $status)
    {
        $clients = Client::join('clients_info', 'clients_info.client_uuid', '=', 'clients.uuid')
            ->join('levels', 'clients.level_uuid', '=', 'levels.uuid')
            ->select([
                  'clients_info.uuid as uuid_client_info',
                  'clients_info.first_name',
                  'clients_info.last_name',
                  'clients_info.birth_date',
                  'clients_info.gender',
                  'clients_info.dui',
                  'clients_info.address',
                  'clients_info.city_id',
                  'clients_info.email',
                  'clients_info.alternative_number_phone',
                  'clients_info.created_at as clients_info_created_at',
                  'clients_info.updated_at as clients_info_updated_at',

                  'clients.uuid as client_uuid',
                  'clients.uuid',
                  'clients.number_phone',
                  'clients.verified',
                  'clients.level_uuid',
                  'clients.status',
                  'clients.invitation_code',
                  'clients.comment',
                  'clients.created_at as client_created_at',
                  'clients.updated_at as client_updated_at',

                  'levels.name',
            ]);

        switch ($status) {
            case 'completed':
                $clients = $clients->whereStatus(Client::COMPLETED);
                break;
            case 'delinquency':
                $clients = $clients->whereStatus(Client::ARREARS);
                break;
            default:
                $clients;
                break;
        }

        switch ($type) {
            case 'week':

                $lastWeek = Carbon::now()->subDays(7);

                $clients->where('clients.created_at', '>=', $lastWeek);

                break;

            case 'month':

                $lastMonth = Carbon::now()->subMonths(1);
                $clients->where('clients.created_at', '>=', $lastMonth);

                break;

            case 'year':

                $lastYear = Carbon::now()->subYears(1);
                $clients->where('clients.created_at', '>=', $lastYear);

                break;

            case 'all':

                break;

            default:

                $range = explode('|', $type);

                $start =    Carbon::parse($range[0]);
                $end   =      Carbon::parse($range[1]);

                $clients->where('clients.created_at', '>=', $start)->where('clients.created_at', '<=', $end);

                break;
        }

        return response()->json(ClientDashboardResource::collection($clients->get()), 200);
    }

    //TODO: Improvement action
    public function detailsClient($client_info)
    {
        $client = ClientInfo::join('clients', 'clients.uuid', '=', 'clients_info.client_uuid')
            ->select('*')
            ->where('clients_info.uuid', '=', $client_info)
            ->first();

        $setting = Setting::where('key', '=', Setting::MAXIMUM_TO_APPLY)->first();

        $loan = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
        ->whereClientUuid($client->uuid)
        ->where('loan_details.status', '=', true);

        $loan_all = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
            ->whereClientUuid($client->uuid)
            ->select([
                  'loans.uuid as loan_uuid',
                  'loans.status as loan_status',
                  'loans.client_uuid',
                  'loans.level_amount_uuid',
                  'loans.comment',
                  'loans.wallet_uuid',
                  'loans.created_at',
                  'loan_details.uuid as loans_detail_uuid',
                  'loan_details.number_fee',
                  'loan_details.fee',
                  'loan_details.interest',
                  'loan_details.capital',
                  'loan_details.balance',
                  'loan_details.loan_uuid',
                  'loan_details.status',
                  'loan_details.created_at as loans_detail_created_at',
                  'loan_details.commission',
                  'loan_details.payday',
                  'loan_details.balance_total',
                  'loan_details.balance_debt',
                  'loan_details.balance_total_debt',
                  'loan_details.debt',
            ]);

        $result = [
            'client'            => $client,
            'setting'           => $setting,
            'loan_total'        => optional($loan->first())->balance_total,
            'loan_count'        => count($loan->get()),
            'loan_balance_now'  => optional($loan->first())->balance,
            'all_loan'          => $loan_all->get(),
        ];

        return response()->json($result, 200);
    }

    /**
     * Get profile info belons to client.
     *
     * @param \App\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Client $client)
    {
        $response = DB::table('questions as q')->leftJoin('answers as a', 'q.uuid', '=', 'a.question_uuid')
            ->leftJoin('clients as c', function ($join) use ($client) {
                $join->on('c.uuid', '=', 'a.client_uuid')
                    ->where('a.client_uuid', $client->uuid);
            })
            ->where('q.is_profile', true)
            ->select(DB::raw('q.text, q.type, a.response, q.uuid as question_uuid, a.uuid as answer_uuid'))
            ->orderBy('q.order')
            ->get();

        return response()->json($response, 200);
    }

    /**
     * Store data from client phone.
     *
     * @param \App\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request, Client $client)
    {
        $data        = collect($request->all());
        $client_uuid = $client->uuid;

        $data->each(function ($item, $key) use ($client_uuid) {
            collect($item)->each(function ($i) use ($key, $client_uuid) {
                $i;

                if (isset($i['uuid'])) {
                    $client_data = ClientData::where('type_uuid', $i['uuid'])->first();
                    if (isset($client_data)) {
                        $client_data->content = collect($i);
                        $client_data->update();
                    } else {
                        $client_data = new ClientData();
                        $client_data->client_uuid = $client_uuid;
                        $client_data->type_uuid = $i['uuid'];
                        $client_data->type = $key;
                        $client_data->content = collect($i);
                        $client_data->save();
                    }
                } else {
                    $client_data = new ClientData();
                    $client_data->client_uuid = $client_uuid;
                    $client_data->type = $key;
                    $client_data->content = collect($i);
                    $client_data->save();
                }
            });
        });

        return response()->json(['message' => 'The data were saved.']);
    }

    public function pushNotification($token, $amount, $type = null)
    {
        $target        = $token;
        $format_amount = money_format('%i', $amount);
        $approved      = "Tu préstamos por $format_amount ha sido aprobado, ingresa a tu billetera Tigo Money para verificar el desembolso de tu préstamo.";
        $dummy         = "Hoy día de pago de tu cuota por $format_amount, ingresa a tu billetera Tigo Money y realizar el pago o acércate el agente Tigo Money más cercano";
        $notification  = [
            'title'    => 'dummy' == $type ? 'Recordartorio de Pago' : 'Aprobación de prestamo',
            'body'     => 'dummy' == $type ? $dummy : $approved,
            'badge'    => '0',
            'sound'    => 'default',
            'priority' => 'high',
        ];

        $apiUrl    = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = 'AIzaSyBuS7bJ5TdgO9hHCj7Dw7kSNiuNctLyXKc';

        $fields                 = [];
        $fields['notification'] = $notification;

        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }

        $fields['priority'] = 'high';

        $headers = [
            'Content-Type:application/json',
            'Authorization:key='.$serverKey,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        if (false === $result) {
            $errors = curl_error($ch);

            // return response()->json([
            //   'success' => false,
            //   'errors' => $errors,
            // ]);

            return false;
        }

        // return response()->json([
        //   'success' => true,
        //   'data' => json_decode($result),
        // ]);
        \Log::debug($result);

        return true;
    }

    public function token(Request $request, Client $client)
    {
        if ($token = $request->get('token')) {
            $client->token = $token;
            $client->update();

            return response()->json(['message' => 'Token updated'], 200);
        }

        return response()->json(['message' => 'Cannot update token'], 401);
    }

    public function notification(Client $client)
    {
        $loan = Loan::where('client_uuid', $client->uuid)->where('status', Loan::ACCEPTED)->first();
        $fee  = $loan->detail()->first()->fee;
        $this->pushNotification($client->token, $fee, 'dummy');

        return response()->json(['successful' => 'Ok'], 200);
    }
}

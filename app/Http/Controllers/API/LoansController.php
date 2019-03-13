<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\Helpers\Transactions;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\LoanResource;
use App\Loan;
use App\LoanDetail;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Loan instance.
     *
     * @var \App\Loan
     */
    protected $loan;

    /**
     * Client instance.
     *
     * @var \App\Client
     */
    protected $client;

    /**
     * Transaction instance.
     *
     * @var \App\Transaction
     */
    protected $transactions;
    protected $settings;

    /**
     * Create instance controller.
     *
     * @param \App\Loan        $loan
     * @param \App\Client      $client
     * @param \App\Transaction $transaction
     */
    public function __construct(Loan $loan, Client $client, Transactions $transactions, Setting $settings)
    {
        $this->loan         = $loan;
        $this->client       = $client;
        $this->transactions = $transactions;
        $this->settings     = $settings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreLoanRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLoanRequest $request)
    {
        $client_uuid   = $request->get('client_uuid');
        $client        = $this->client->whereUuid($client_uuid)->first();
        $latest_loan   = $this->loan->orderBy('created_at', 'desc')->first();
        $this->loan->fill($request->all());
        $this->loan->identifier = strtoupper(now()->year.'-'.substr($client->info->first_name, 0, 1).substr($client->info->last_name, 0, 1));
        if ($latest_loan) {
            if (null == $latest_loan->correlative) {
                $this->loan->correlative = 1000;
            } else {
                $this->loan->correlative = (int) $latest_loan->correlative + 1;
            }
        } else {
            $this->loan->correlative = 1000;
        }

        if ($this->loan->save()) {
            $client         = $this->client->whereUuid($client_uuid)->first();
            $client->status = Client::IN_PROCESS;
            $client->update();

            return new LoanResource($this->loan);
        }

        return response()->json(['error' => 'The given data was invalid.'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Loan $loan
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Loan $loan)
    {
        $details            = $loan->detail;
        $count              = $loan->detail()->count();
        $actual_quote       = $details->whereIn('status', [LoanDetail::ACTIVE, LoanDetail::DUE])->first();
        $percentage         = round($this->settings->value('TASA_ANUAL') / ($count > 1 ? 52.143 : 12), 2);
        $loan_total         = $actual_quote->debt ? $actual_quote->debt + $loan->detail()->sum('fee') : $loan->detail()->sum('fee');
        $payment_amount     = $actual_quote->debt ? $actual_quote->debt + $loan->detail()->first()->fee : $loan->detail()->first()->fee;

        return response()->json([
            'loan_requested_amount' => money_format('%i', $loan->amount->amount),
            'loan_total_amount'     => money_format('%i', $loan_total),
            'loan_interest'         => $percentage,
            'loan_payment'          => money_format('%i', $loan->detail()->sum('interest') + $loan->detail()->sum('commission')),
            'time_period'           => $count > 1 ? '7 días' : '31 días',
            'payment_amount'        => money_format('%i', $payment_amount),
        ]);
    }

    /**
     * Accept loan previously approved.
     *
     * @param Loan $loan
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Loan $loan)
    {
        // if ($loan->isApproved()) {
        $loan->fill(['status' => Loan::ACCEPTED]);

        if ($loan->update()) {
            return response()->json(['message' => 'Loan status was updated'], 200);
        }
        // }

        // return response()->json(['message' => 'Loan is not approved'], 200);
    }

    /**
     * Get status loan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $client =$this->client
                      ->whereNumberPhone($request->get('number_phone'))
                      ->first();

        $detail = $client->loans
                         ->where('status', 3)
                         ->first()
                         ->detail
                         ->where('status', 1)
                         ->first();

        return response()->json([
            'number_phone_client' => $client->number_phone,
            'number_fee'          => $detail->number_fee,
            'fee'                 => $detail->fee,
            'commission'          => $detail->commission,
            'interest'            => $detail->interest,
            'balance'             => $detail->balance,
        ]);
    }

    //TODO: Improvement action
    public function getAllLoan($type)
    {
        // $loan = Loan::whereStatus(Loan::ACCEPTED)->get();

        $loans = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
            ->join('clients', 'clients.uuid', '=', 'loans.client_uuid')
            ->join('clients_info', 'clients_info.client_uuid', '=', 'clients.uuid')
            ->select([
                'clients_info.first_name',
                'clients_info.last_name',
                'loan_details.capital as amount',
                'loans.status',
                'loans.created_at',
                'loans.uuid as loan_uuid',
                'loan_details.uuid as loans_detail_uuid',
            ]);

        switch ($type) {
            case 'approved':
                $loans = $loans->where('loans.status', '=', Loan::APPROVED)
                    ->get();
                break;
            case 'deny':
                $loans = $loans->where('loans.status', '=', Loan::REJECTED)
                    ->get();
                break;
            case 'pending':
                $loans = $loans->where('loans.status', '=', Loan::PENDING)
                    ->get();
                break;
            case 'made':
                $loans = $loans->where('loans.status', '=', Loan::ACCEPTED)
                    ->get();
                break;
            case 'paid':
                $loans = $loans->where('loans.status', '=', Loan::PAID)
                    ->get();
                break;
            default:
                // all
                $loans = $loans->get();
                break;
        }

        $loans_row = [];

        foreach ($loans as $loan) {
            array_push($loans_row, [
                'first_name'        => $loan->first_name,
                'last_name'         => $loan->last_name,
                'amount'            => $loan->amount,
                'status'            => Loan::STATUS_STRING[$loan->status],
                'created_at'        => $loan->created_at->format('m-d-Y'),
                'loan_uuid'         => $loan->loan_uuid,
                'loans_detail_uuid' => $loan->loans_detail_uuid,
            ]);
        }

        $now = Carbon::now()->format('Y-m-d');

        $loans = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
            //->where('loans.status', '=', Loan::ACCEPTED)
;
        $expired_loans          = $loans->where('loan_details.payday', '>', $now)->get();
        $loans                  = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
            //->where('loans.status', '=', Loan::ACCEPTED)
;
        $loans_pay_today        = $loans->where('loan_details.payday', '=', $now)->get();
        $loans                  = Loan::join('loan_details', 'loan_details.loan_uuid', '=', 'loans.uuid')
            //->where('loans.status', '=', Loan::ACCEPTED)
;
        $loans_without_default  = $loans->where('loan_details.payday', '<=', $now)->get();

        $result = [
            'loans'         => $loans_row,
            'counts'        => [
                'expired_loans'         => count($expired_loans),
                'loans_pay_today'       => count($loans_pay_today),
                'loans_without_default' => count($loans_without_default),
                'loans_with_arrears'    => count($expired_loans),
            ],
        ];

        return response()->json($result, 200);
    }

    //TODO: Improvement action
    public function loanDetail($detail_uuid)
    {
        $loanDetails = LoanDetail::join('loans', 'loans.uuid', '=', 'loan_details.loan_uuid')
            ->where('loan_details.uuid', '=', $detail_uuid)
        ->select([
            'loan_details.capital',
            'loan_details.balance',
            'loan_details.status',
            'loans.status as status_loan',
        ])->get();

        $row = [];

        // deberia de devolver siempre 1 registro por ser una tabla 1 a 1 pero por si acaso
        // lo creo iterable

        foreach ($loanDetails as $loanDetail) {
            array_push($row, [
                'amount_loan'           => $loanDetail->capital,
                'loan_with_interest'    => $loanDetail->balance,
                'date_pay'              => '2018-11-05', // desconocido en base de datos
                'status'                => true === $loanDetail->status ? 'true' : 'false',
                'status_loan'           => Loan::STATUS_STRING[$loanDetail->status_loan],
            ]);
        }

        return response()->json($row, 200);
    }

    //TODO: Improvement action
    public function getAllLoanCount($type)
    {
        $loan_pending           = Loan::whereStatus(Loan::PENDING);
        $loan_aproved           = Loan::whereStatus(Loan::APPROVED);
        $loan_accepted          = Loan::whereStatus(Loan::ACCEPTED);
        $loan_rejected          = Loan::whereStatus(Loan::REJECTED);
        $loan_paid              = Loan::whereStatus(Loan::PAID);
        $profile_completed      = Client::whereStatus(Client::COMPLETED);
        $delinquency_profile    = Client::whereStatus(Client::ARREARS);

        switch ($type) {
            case 'week':

                $lastWeek = Carbon::now()->subDays(7);

                $results = [
                    'loan_pending'        => $this->getCountCalendarWeek($loan_pending->where('created_at', '>=', $lastWeek)),
                    'loan_aproved'        => $this->getCountCalendarWeek($loan_aproved->where('created_at', '>=', $lastWeek)),
                    'loan_accepted'       => $this->getCountCalendarWeek($loan_accepted->where('created_at', '>=', $lastWeek)),
                    'loan_rejected'       => $this->getCountCalendarWeek($loan_rejected->where('created_at', '>=', $lastWeek)),
                    'loan_paid'           => $this->getCountCalendarWeek($loan_paid->where('created_at', '>=', $lastWeek)),
                    'profile_completed'   => $this->getCountCalendarWeek($profile_completed->where('created_at', '>=', $lastWeek)),
                    'delinquency_profile' => $this->getCountCalendarWeek($delinquency_profile->where('created_at', '>=', $lastWeek)),
                ];

                break;

            case 'month':

                $lastMonth = Carbon::now()->subMonths(1);

                $results = [
                    'loan_pending'        => $this->getCountCalendarWeekOfMonth($loan_pending->where('created_at', '>=', $lastMonth)),
                    'loan_aproved'        => $this->getCountCalendarWeekOfMonth($loan_aproved->where('created_at', '>=', $lastMonth)),
                    'loan_accepted'       => $this->getCountCalendarWeekOfMonth($loan_accepted->where('created_at', '>=', $lastMonth)),
                    'loan_rejected'       => $this->getCountCalendarWeekOfMonth($loan_rejected->where('created_at', '>=', $lastMonth)),
                    'loan_paid'           => $this->getCountCalendarWeekOfMonth($loan_paid->where('created_at', '>=', $lastMonth)),
                    'profile_completed'   => $this->getCountCalendarWeekOfMonth($profile_completed->where('created_at', '>=', $lastMonth)),
                    'delinquency_profile' => $this->getCountCalendarWeekOfMonth($delinquency_profile->where('created_at', '>=', $lastMonth)),
                ];
                break;

            case 'year':

                $lastYear = Carbon::now()->subYears(1);

                $results = [
                    'loan_pending'        => $this->getCountCalendarMonth($loan_pending->where('created_at', '>=', $lastYear)),
                    'loan_aproved'        => $this->getCountCalendarMonth($loan_aproved->where('created_at', '>=', $lastYear)),
                    'loan_accepted'       => $this->getCountCalendarMonth($loan_accepted->where('created_at', '>=', $lastYear)),
                    'loan_rejected'       => $this->getCountCalendarMonth($loan_rejected->where('created_at', '>=', $lastYear)),
                    'loan_paid'           => $this->getCountCalendarMonth($loan_paid->where('created_at', '>=', $lastYear)),
                    'profile_completed'   => $this->getCountCalendarMonth($profile_completed->where('created_at', '>=', $lastYear)),
                    'delinquency_profile' => $this->getCountCalendarMonth($delinquency_profile->where('created_at', '>=', $lastYear)),
                ];
                break;

            default:

                $range = explode('|', $type);

                $start =    Carbon::parse($range[0]);
                $end   =      Carbon::parse($range[1]);

                $results = [
                    'loan_pending'        => $this->getCountCalendarMonth($loan_pending->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'loan_aproved'        => $this->getCountCalendarMonth($loan_aproved->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'loan_accepted'       => $this->getCountCalendarMonth($loan_accepted->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'loan_rejected'       => $this->getCountCalendarMonth($loan_rejected->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'loan_paid'           => $this->getCountCalendarMonth($loan_paid->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'profile_completed'   => $this->getCountCalendarMonth($profile_completed->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                    'delinquency_profile' => $this->getCountCalendarMonth($delinquency_profile->where('created_at', '>=', $start)->where('created_at', '<=', $end)),
                ];
                break;
        }

        return response()->json($results, 200);
    }

    private function getCountCalendarWeek($model)
    {
        $weekMap = [
            0 => 'SU',
            1 => 'MO',
            2 => 'TU',
            3 => 'WE',
            4 => 'TH',
            5 => 'FR',
            6 => 'SA',
        ];

        $weekCount = [
            'SU' => 0,
            'MO' => 0,
            'TU' => 0,
            'WE' => 0,
            'TH' => 0,
            'FR' => 0,
            'SA' => 0,
        ];

        $totalCount = 0;

        foreach ($model->select('created_at')->get() as $row) {
            $dayWeek  = Carbon::parse($row->created_at)->dayOfWeek;
            $weekName = $weekMap[$dayWeek];
            ++$weekCount[$weekName];
            ++$totalCount;
        }

        return [
            'weekCount'     => $weekCount,
            'totalCount'    => $totalCount,
        ];
    }

    private function getCountCalendarWeekOfMonth($model)
    {
        $weekMap = [
            1 => 'ONE',
            2 => 'TWO',
            3 => 'THR',
            4 => 'FOU',
        ];

        $weekCount = [
            'ONE' => 0,
            'TWO' => 0,
            'THR' => 0,
            'FOU' => 0,
        ];

        $totalCount = 0;

        foreach ($model->select('created_at')->get() as $row) {
            $week     = Carbon::parse($row->created_at)->weekOfMonth;
            $weekName = $weekMap[5 === $week ? 4 : $week];
            ++$weekCount[$weekName];
            ++$totalCount;
        }

        return [
            'weekOfMonth'   => $weekCount,
            'totalCount'    => $totalCount,
        ];
    }

    private function getCountCalendarMonth($model)
    {
        $monthMap = [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $monthCount = [
            'January'  => 0,
            'February' => 0,
            'March'    => 0,
            'April'    => 0,
            'May'      => 0,
            'June'     => 0,
            'July'     => 0,
            'August'   => 0,
            'September'=> 0,
            'October'  => 0,
            'November' => 0,
            'December' => 0,
        ];

        $totalCount = 0;

        foreach ($model->select('created_at')->get() as $row) {
            $month     = Carbon::parse($row->created_at)->month;
            $MonthName = $monthMap[$month];
            ++$monthCount[$MonthName];
            ++$totalCount;
        }

        return [
            'month'         => $monthCount,
            'totalCount'    => $totalCount,
        ];
    }

    //TODO: Improvement action
    public function check(Request $request)
    {
        $client = $this->client
                       ->whereNumberPhone($request->get('number_phone'))
                       ->first();

        $loan = $client->loans
                       ->where('status', Loan::ACCEPTED)
                       ->first();

        $detail = $loan->detail
                       ->where('status', 1)
                       ->first();

        $now = now()->addDays(5);

        if (! $detail->payday->gt($now)) {
            $debt_days = $detail->payday->diffInDays($now);
            if (1 == $detail->number_fee) {
                $debt = $this->transactions
                             ->debt($loan->amount->amount,
                                    $detail->sum('fee'),
                                    $debt_days,
                                    $detail->fee, $loan);
            } else {
                $debt = $this->transactions
                             ->debt($detail->balance,
                                    $detail->sum('fee'),
                                    $debt_days,
                                    $detail->fee, $loan);
            }
            $loan_detail = LoanDetail::where('uuid', $detail->uuid)->first();

            $loan_detail->debt               = $debt['charge_late_payment'];
            $loan_detail->balance_debt       = $debt['pay_debt'];
            $loan_detail->balance_total_debt = $debt['debt_total'];
            $loan_detail->update();

            return response()->json($loan_detail);
        }

        return response()->json([
            'number_phone_client' => $client->number_phone,
            'number_fee'          => $detail->number_fee,
            'fee'                 => $detail->fee,
            'commission'          => $detail->commission,
            'interest'            => $detail->interest,
            'balance'             => $detail->balance,
            'debt'                => $detail->debt,
            'balance_debt'        => $detail->balance_debt,
            'balance_total_debt'  => $detail->balance_total_debt,
        ]);
    }

    public function details(Loan $loan)
    {
        $data = collect($this->transactions->amortization($loan->amount->amount));

        $semanal = [];
        $count   = 0;
        foreach ($data['amortization'] as $key => $value) {
            $semanal[$count]['date']     = $value['payday'];
            $semanal[$count]['type']     = "Pago $key de 4";
            $semanal[$count]['amount']   = $value['fee'];
            $semanal[$count]['interest'] = round($value['interest'], 2);
            $semanal[$count]['capital']  = round($value['capital'], 2);
            $semanal[$count]['balance']  = round($value['balance'], 2);
            ++$count;
        }
        $percentage_semanal = round($this->settings->value('TASA_ANUAL') / 52, 2);
        $percentage_mensual = round($this->settings->value('TASA_ANUAL') / 12, 2);
        $interest_semanal   = collect($data['amortization'])->sum('fee') - $loan->amount->amount;
        $semanal            = array_merge(['payments' => $semanal], [
            'uuid'              => $loan->uuid,
            'period'            => '7 días',
            'amount'            => money_format('%i', $loan->amount->amount),
            'loan_total_amount' => round(collect($data['amortization'])->sum('fee'), 2),
            'loan_payment'      => '25%',
            'loan_interest'     => "$percentage_semanal% - $interest_semanal",
            'type'              => 'M',
        ]);

        return response()->json([
            'loan_requested_amount' => $loan->amount->amount,
            'loan_total_amount'     => collect($data['amortization'])->sum('fee'),
            'loan_interest'         => $loan->detail()->sum('interest'),
            'loan_payment'          => '25%',
            'time_period'           => '7 días',
            'payment_amount'        => collect($data['amortization'])->first()->fee,
        ]);
    }
}

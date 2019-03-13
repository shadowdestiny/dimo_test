<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\GlobalAmortization;
use App\Helpers\Transactions;
use App\Http\Controllers\Controller;
use App\Loan;
use App\LoanDetail;
use App\Setting;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    protected $transactions;
    protected $loans;
    protected $settings;

    public function __construct(Transactions $transactions, Loan $loans, Setting $settings)
    {
        $this->transactions  = $transactions;
        $this->loans         = $loans;
        $this->settings      = $settings;
    }

    //TODO: Improvement action
    public function amortization(Request $request)
    {
        return $this->transactions->amortization($request->get('amount'));
    }

    //TODO: Improvement action
    public function accepted(Request $request)
    {
        $loan_uuid  = $request->get('loan_uuid');
        $loan       = $this->loans->whereUuid($loan_uuid)->first();
        $client     = Client::where('uuid', $loan->client_uuid)->first();
        $commission = $client->level->commission;
        $data       = collect($this->transactions->amortization($loan->amount->amount, $request->get('type'), $commission));

        collect($data['amortization'])->each(function ($item, $key) use ($loan_uuid, $loan) {
            $loan_detail = new LoanDetail();
            $loan_detail->loan_uuid = $loan_uuid;
            $loan_detail->number_fee = $key;
            $loan_detail->fill($item);
            if ($loan_detail->save()) {
                $loan->status = Loan::ACCEPTED;
                $loan->update();
            }
        });

        collect($data['global'])->each(function ($item, $key) use ($loan_uuid, $loan) {
            $global = new GlobalAmortization();
            $global->loan_uuid = $loan_uuid;
            $global->fill($item);
            $global->save();
        });
        $client->status = Client::ACTIVE;
        $client->update();

        return response()->json(['message' => 'Loan detail were saved.']);
    }

    public function generate(Request $request)
    {
        $loan_uuid      = $request->get('loan_uuid');
        $loan           = $this->loans->whereUuid($loan_uuid)->first();
        $commission     = $loan->client->level->commission;
        $data           = collect($this->transactions->amortization($loan->amount->amount, 'Q', $commission));
        $data2          = collect($this->transactions->amortization($loan->amount->amount, 'M', $commission));

        $semanal   = [];
        $mensual   = [];
        $count     = 0;
        $count2    = 0;
        foreach ($data['amortization'] as $key => $value) {
            $semanal[$count]['date']      = $value['payday'];
            $semanal[$count]['type']      = "Pago $key de 4";
            $semanal[$count]['amount']    = money_format('%i', $value['fee']);
            $semanal[$count]['interest']  = money_format('%i', $value['interest']);
            $semanal[$count]['capital']   = money_format('%i', $value['capital']);
            $semanal[$count]['balance']   = money_format('%i', $value['balance']);
            ++$count;
        }
        $percentage_semanal = round($this->settings->value('TASA_ANUAL') / 52.143, 2);
        $percentage_mensual = round($this->settings->value('TASA_ANUAL') / 12, 2);
        $interest_semanal   = collect($data['amortization'])->sum('fee') - $loan->amount->amount;
        $semanal            = array_merge(['payments' => $semanal], [
            'uuid'              => $loan->uuid,
            'period'            => '7 días',
            'amount'            => money_format('%i', $loan->amount->amount),
            'loan_total_amount' => money_format('%i', collect($data['amortization'])->sum('fee')),
            'loan_payment'      => money_format('%i', $interest_semanal),
            'loan_interest'     => "$percentage_semanal%",
            'type'              => 'Q',
            'active'            => true,
        ]);
        foreach ($data2['amortization'] as $key => $value) {
            $mensual[$count2]['date']     = $value['payday'];
            $mensual[$count2]['type']     = "Pago $key de 1";
            $mensual[$count2]['amount']   = money_format('%i', $value['fee']);
            $mensual[$count2]['interest'] = money_format('%i', $value['interest']);
            ++$count2;
        }
        $interest_mensual = collect($data2['amortization'])->sum('fee') - $loan->amount->amount;

        $mensual = array_merge(['payments' => $mensual], [
            'uuid'              => $loan->uuid,
            'period'            => '30 días',
            'amount'            => money_format('%i', $loan->amount->amount),
            'loan_total_amount' => money_format('%i', collect($data2['amortization'])->sum('fee')),
            'loan_payment'      => money_format('%i', $interest_mensual),
            'loan_interest'     => "$percentage_mensual%",
            'type'              => 'M',
            'active'            => true,
        ]);
        $amortization = collect($data['amortization'])->sum('interest');

        return response()->json([
            'loan_requested_amount' => money_format('%i', $loan->amount->amount),
            'loan_total_amount'     => money_format('%i', collect($data['amortization'])->sum('fee')),
            'loan_interest'         => $loan->detail()->sum('interest'),
            'loan_payment'          => '25%',
            'periods'               => [$semanal, $mensual],
        ]);
    }

    public function test(Request $request)
    {
        $loan_uuid = $request->get('loan_uuid');
        $type      = $request->get('type');
        $loan      = $this->loans->whereUuid($loan_uuid)->first();

        return collect($this->transactions->amortization(25, $type, 2));
    }
}

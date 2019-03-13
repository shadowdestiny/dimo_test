<?php

namespace App\Jobs;

use App\Client;
use App\Helpers\Transactions;
use App\Loan;
use App\LoanDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $clients = Client::whereVerified(1)->get();

        foreach ($clients as $client) {
            $loan = $client->loans
                ->whereIn('status', [Loan::ACCEPTED, Loan::DUE])
                ->first();

            if ($loan) {
                $detail = $loan->detail
                    ->whereIn('status', [LoanDetail::ACTIVE, LoanDetail::DUE])
                    ->first();

                $now = now();

                if (! $detail->payday->gt($now)) {
                    $debt_days    = $detail->payday->diffInDays($now);
                    if (1 == $detail->number_fee) {
                        $debt = Transactions::calculateDue(
                                $loan->amount->amount,
                                $detail->sum('fee'),
                                $debt_days,
                                $detail->fee,
                                $loan
                            );
                    } else {
                        $debt = Transactions::calculateDue(
                                $detail->balance,
                                $detail->sum('fee'),
                                $debt_days,
                                $detail->fee,
                                $loan
                            );
                    }
                    $loan_detail = LoanDetail::where('uuid', $detail->uuid)->first();

                    $loan_detail->debt               = $debt['charge_late_payment'];
                    $loan_detail->balance_debt       = $debt['pay_debt'];
                    $loan_detail->balance_total_debt = $debt['debt_total'];
                    $loan_detail->status             = 3;
                    \Log::info($loan_detail);
                    $loan_detail->update();

                    if ($loan_detail->debt > 0) {
                        $client->status = 'active_due';
                        if (! $client->start_due_at) {
                            $client->start_due_at = now();
                        }
                        $client->update();

                        $loan->status = Loan::DUE;
                        $loan->update();
                    }
                }
            }
        }
    }
}

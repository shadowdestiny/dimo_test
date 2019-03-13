<?php

namespace App\Jobs;

use App\Client;
use App\Level;
use App\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyLevel implements ShouldQueue
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
        $clients->each(function ($item, $key) {
            $level = $item->level;
            $count_loans = $item->loans()->whereStatus(Loan::PAID)->count();
            $sum_loans = 0;
            $loans = $item->loans()->whereStatus(Loan::PAID)->get();
            foreach ($loans as $loan) {
                $sum_loans += $loan->amount->amount;
            }
            $level_days = $item->start_date_level->diffInDays(now());

            if ($level->order < 3) {
                if ($count_loans >= $level->max_loans) {
                    if ($level_days >= $level->max_time) {
                        if ($sum_loans >= $level->next_level_amount) {
                            $item->level_uuid = Level::whereOrder($level->order + 1)->first()->uuid;
                            $item->start_date_level = now();
                            $item->update();

                            \Log::info('Client updated');

                            return;
                        }
                        \Log::info("Client not updated, client only has $ $sum_loans accumulated in loans.");

                        return;
                    }

                    \Log::info("Client not updated, client only has $level_days days in level.");

                    return;
                }

                \Log::info("Client not updated, client only has $count_loans loans.");

                return;
            }

            \Log::warning('Client not updated, client it\'s in max level.');

            return;
        });
    }
}

<?php

use App\Loan;
use Illuminate\Database\Seeder;

class LoansTableSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(Loan::class, 3)->create();
    }
}

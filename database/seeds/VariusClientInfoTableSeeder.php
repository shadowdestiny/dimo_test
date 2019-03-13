<?php

use App\Client;
use App\Loan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Ramsey\Uuid\Uuid;

class VariusClientInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $status = [
            Client::INITIAL,
            Client::AVAILABLE,
            Client::IN_PROCESS,
            Client::APPROVED,
            Client::ACTIVE,
            Client::REJECTED,
            Client::COMPLETED,
        ];

        $faker = Faker::create();
        foreach (range(1,count($status)) as $index) {
            $uuid = Uuid::uuid4();
            DB::table('clients')->insert([
                [
                    'uuid'         => $uuid,
                    'number_phone' => $faker->phoneNumber,
                    'verified'     => true,
                    'level_uuid'   => DB::table('levels')->where('key', 'diamond')->first()->uuid,
                    'status'       => $status[$index - 1],
                    'created_at'   => now(),
                ],
            ]);

            DB::table('clients_info')->insert([
                [
                    'uuid'                      => Uuid::uuid4(),
                    'first_name'                => $faker->name,
                    'last_name'                 => str_random(10),
                    'birth_date'                => $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-20 years')->format('Y-m-d'),
                    'gender'                    => 'male',
                    'dui'                       => $faker->buildingNumber,
                    'address'                   => $faker->address,
                    'city_id'                   => 1,
                    'email'                     => $faker->email,
                    'alternative_number_phone'  => $faker->phoneNumber,
                    'client_uuid'               => $uuid,
                    'created_at'                => now(),
                ],
            ]);

            $loansStatus = [
                Loan::PENDING,
                Loan::APPROVED,
                Loan::ACCEPTED,
                Loan::REJECTED,
            ];

            foreach($loansStatus as $loansStatusVal){

                $loan_uuid = Uuid::uuid4();
                DB::table('loans')->insert([
                    [
                        'uuid'                      => $loan_uuid,
                        'status'                    => $loansStatusVal,
                        'client_uuid'               => $uuid,
                        'level_amount_uuid'         => Uuid::uuid4(),
                        'comment'                   => $faker->text(100),
                        'wallet_uuid'               => Uuid::uuid4(),
                        'created_at'                => now(),
                    ],
                ]);

                DB::table('loan_details')->insert([
                    [
                        'uuid'                      => Uuid::uuid4(),
                        'number_fee'                => $faker->numberBetween(1,3),
                        'fee'                       => $faker->randomFloat(1,2,2),
                        'interest'                  => $faker->numberBetween(1,1),
                        'capital'                   => $faker->randomFloat(1,2,2),
                        'balance'                   => $faker->randomFloat(1,2,2),
                        'loan_uuid'                 => $loan_uuid,
                        'status'                    => true,
                        'created_at'                => now(),
                        'commission'                => $faker->numberBetween(1,2),
                        'payday'                    => $faker->dateTimeBetween($startDate = '-30 days', $endDate = '-20 days'),
                        'balance_total'             => $faker->randomFloat(1,2,2),
                        'balance_debt'              => $faker->randomFloat(1,2,2),
                        'balance_total_debt'        => $faker->randomFloat(1,2,2),
                        'debt'                      => $faker->randomFloat(1,2,2),
                    ],
                ]);
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(LevelTableSeeder::class);
        $this->call(LevelAmountTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(StepsTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(LoansTableSedeer::class);
        $this->call(WalletBrandsTableSeeder::class);
        $this->call(ClientWalletTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(UsersSeed::class);
        $this->call(ClientInfoTableSeeder::class);
        $this->call(AnswersTableSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreditPlan;
class AddCreditPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreditPlan::truncate();
        $credit = new CreditPlan;
        $credit->title = '1000 Credits';
        $credit->credits = '1000';
        $credit->price = '9';
        $credit->comment = 'Extra 50+ credits included';
        $credit->save();

        $credit1 = new CreditPlan;
        $credit1->title = '6000 Credits';
        $credit1->credits = '6000';
        $credit1->price = '49';
        $credit1->comment = 'Extra 300+ credits included';
        $credit1->save();

        $credit2 = new CreditPlan;
        $credit2->title = '12500 Credits';
        $credit2->credits = '12500';
        $credit2->price = '99';
        $credit2->comment = 'Extra 2800+ credits included';
        $credit2->save();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $duration = [
            [
                "type" => "monthly", 
                "duration" => 30,
                "amount" => 1200.00,
            ],
            [
                "type" => "quarterly",
                "duration" => 90,
                "amount" => 3500.00,
            ],
            [
                "type" => "semi_yearly",
                "duration" => 180,
                "amount" => 6500.00,
            ],
            [
                "type" => "yearly", 
                "duration" => 360,
                "amount" => 12000.00,
            ],
            [
                "type" => "free",
                "duration" => 7,
                "amount" => 0.00,
            ]
        ];
        foreach($duration as $value){
            Subscription::updateOrCreate(
                [
                    'subscription_type' => $value['type'],
                ],
                [
                    'duration'=> $value['duration'],
                    'amount' => $value['amount']
            ]);
        }
    }
}

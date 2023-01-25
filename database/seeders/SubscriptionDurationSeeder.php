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
                "duration" => 30
            ],
            [
                "type" => "quarterly",
                "duration" => 90
            ],
            [
                "type" => "semi_yearly",
                "duration" => 180
            ],
            [
                "type" => "yearly", 
                "duration" => 360
            ],
            [
                "type" => "free",
                "duration" => 7
            ]
        ];
        foreach($duration as $value){
            Subscription::updateOrCreate(
                [   'subscription_type' => $value['type'],
                    'duration'=> $value['duration']
                ]);
        }
    }
}

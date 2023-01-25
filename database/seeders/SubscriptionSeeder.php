<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = [
            ['subscription_type'=>'free',
            'amount'=>'0.00'],
        ];
        foreach($subscriptions as $subscription){
            Subscription::firstOrCreate([
                'subscription_type' => $subscription['subscription_type'],
                'amount' => $subscription['amount']
            ]
            );
        }

    }
}

<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            "type" => 'vendor_youtube_link',
            "value" => 'https://www.youtube.com/watch?v=5juujL9QReQ'
        ],
        [
            "type" => 'youtube_link',
            "value" => 'https://www.youtube.com/watch?v=5juujL9QReQ'
        ]];
        foreach($data as $value){
            GeneralSetting::insert([
                'type' => $value['type'],
                'value' => $value['value']
            ]);
        }
    }
}

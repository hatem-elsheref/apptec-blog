<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'general' => [
                [
                    'key'   => 'site_title',
                    'value' => 'Blog',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'site_close_date',
                    'value' => 'Blog',
                    'type'  => 'datetime',
                ],
                [
                    'key'   => 'site_logo',
                    'value' => 'google.png',
                    'type'  => 'file',
                ],
                [
                    'key'   => 'site_frontend_pagination',
                    'value' => 6,
                    'type'  => 'number',
                ]
            ]
        ];

        foreach ($settings as $group => $items){
            foreach ($items as $item){
                Setting::query()->create([
                    'group' => $group,
                    ...$item
                ]);
            }
        }
    }
}

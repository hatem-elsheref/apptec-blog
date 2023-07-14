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
                    'order' => 1,
                ],
                [
                    'key'   => 'site_close_date',
                    'value' => 'Blog',
                    'type'  => 'date',
                    'order' => 2,
                ],
                [
                    'key'   => 'site_logo',
                    'value' => 'GOOG.png',
                    'type'  => 'file',
                    'order' => 4,
                    'additional' => [
                        'validation' => 'required|file|mimes:png|jpg|jpeg|max:2048',
                        'html' => [
                            'accept'  => 'image/png,image/jpg,image/jpeg',
                        ]
                    ]
                ],
                [
                    'key'   => 'site_frontend_pagination',
                    'value' => 6,
                    'type'  => 'number',
                    'order' => 3,
                    'additional' => [
                        'validation' => 'required|numeric|min:1',
                        'html' => [
                            'min'  => 1,
                            'step' => 1,
                        ]
                    ]
                ]
            ]
        ];

        foreach ($settings as $group => $items){
            foreach ($items as $item){
                if (isset($item['additional'])) $item['additional'] = json_encode($item['additional']);
                Setting::query()->create([
                    'group' => $group,
                    ...$item
                ]);
            }
        }
    }
}

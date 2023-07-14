<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();

         User::factory()->create([
             'name'     => 'Hatem Mohamed',
             'email'    => 'hatem_mohamed_elsheref@yahoo.com',
             'is_admin' => true,
         ]);

         $this->call(PostSeeder::class);

         $this->call(ReactSeeder::class);

         $this->call(CommentSeeder::class);

         $this->call(SettingSeeder::class);

         Artisan::call('optimize:clear');
    }
}

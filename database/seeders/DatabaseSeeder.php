<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'=> 'admin',
            'email'=> 'admin@gmail.com',
            'password' => app('hash')->make('admin@gmail.com')
        ]);
        // $this->call('UsersTableSeeder');
    }
}

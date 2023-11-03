<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Faker\Factory as Faker;


class InputAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'input-admin:corn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Input admin account to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('[START] Input admin account to database');
        $faker = Faker::create();   
        $username = $faker->userName;
        $email = $username . '@gmail.com';
        $password = $faker->password;

        $user = new User;
        $user->username = $username;
        $user->email = $email;
        $user->password = app('hash')->make($password);
       
        $substr = substr($username, 0, 1);
        if($substr == "b") {
            $this->info("[FINISH] Failed input admin account to database");
        } else {
            $user->save();
    
            $this->info('Create user: ' . $username);
            $this->info("[FINISH] Success input admin account to database");
        }
    }
}

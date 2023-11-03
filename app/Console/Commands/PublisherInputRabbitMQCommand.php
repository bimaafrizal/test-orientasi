<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Faker\Factory as Faker;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublisherInputRabbitMQCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Input & send data rabbitmq';


    private $connection;
    private $channel;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $this->channel = $this->connection->channel();
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
        $user->save();

        $msg = new AMQPMessage($user);
        $this->channel->exchange_declare(env('RABBITMQ_EXCHANGE'), 'direct', false, false, false);
        $this->channel->basic_publish($msg, env('RABBITMQ_EXCHANGE'), env('RABBITMQ_ROUTING_KEY'));

        $this->info('Create user: ' . $username);
        $this->info("[FINISH] Success input admin account to database");
    }
}

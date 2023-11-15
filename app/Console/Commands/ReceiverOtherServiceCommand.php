<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ReceiverOtherServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:receive-other-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receiver Other Service Command';
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
        $this->connection = $this->connection = new AMQPStreamConnection(
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
        $channel = $this->channel;
        $channel->queue_declare('service_b_queue', false, false, false, false);


        $callback = function ($msg) use ($channel) {
            echo ' [x] Received ', $msg->body, "\n";
            // $data = json_decode($msg->body, true);

            // Proses data dari Service A
            // Lakukan operasi yang diperlukan

            // Kirim respons kembali ke Service A
            // $response = ['message' => 'Data processed by Service B'];
            $replyTo = $msg->get('reply_to');

            $correlationId = $msg->body;
            $msg = new AMQPMessage(json_encode(User::all()), [
                'correlation_id' => $correlationId,
            ]);
            $channel->basic_publish(
                $msg,
                '',
                $replyTo,
            );
        };

        $channel->basic_consume('service_b_queue', '', false, false, false, false, $callback);
        echo " [x] SEND DATA TO OTHER SERVICE\n";

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();

        // $this->channel->exchange_declare(env('RABBITMQ_EXCHANGE'), 'direct', false, false, false);

        // $this->channel->queue_declare(env('RABBITMQ_QUEUE'), false, true, false, false);
        // $this->channel->queue_bind(env('RABBITMQ_QUEUE'), env('RABBITMQ_EXCHANGE'), env('RABBITMQ_ROUTING_KEY'));

        // echo " [*] Waiting for messages. To exit press CTRL+C\n";

        // $callback = function ($msg) {
        //     echo ' [x] Received ', $msg->body, "\n";
        //     $msg = new AMQPMessage(
        //         'Berikut adalah data user!' . User::all());

        //     $this->channel->basic_publish($msg, '', env('RABBITMQ_ROUTING_KEY'));
        // };

        // $this->channel->basic_consume(env('RABBITMQ_QUEUE'), '', false, true, false, false, $callback);

        // while(count($this->channel->callbacks)) {
        //     $this->channel->wait();
        // }
    }
}

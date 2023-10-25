<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AdminController extends Controller
{
    public function showAllDatas()
    {
        return response()->json([
            'code' => 200,
            'message' => 'successufully get all data admin',
            'data' => User::all()]);
    }

    public function showOneData($id)
    {
        $data = User::find($id);
        if (!$data) {
            return $this->notFound();
        }

        return response()->json([
            'code' => 200,
            'message' => 'successufully get one data admin',
            'data' => User::find($id)]);
    }

    public function create(Request $request)
    {   
        $validation = $this->validate($request, [
            'username' => 'required|min:3|max:255|unique:users,username',
            'email' => 'unique:users,email|required|email:dns',
            'password' => 'required|min:8|max:255'
        ]);

        $data = new User;
        $data->username = $validation['username'];
        $data->email = $validation['email'];
        $data->password =  app('hash')->make($validation['password']);
        $data->save();

        return response()->json([
            'code' => 201,
            'message' => 'successufully create one admin',
            'data' => $data], 201);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'username' => ['required', 'min:3', 'max:255', 'unique:users,username.'.$id],
            'email' => ['required', 'email:dns', 'unique:users,email.'.$id],
            'password' => 'nullable|min:8|max:255'
        ]);
        
        $data = User::find($id);
        if (!$data) {
            return $this->notFound();
        }

        $data->username = $request->input('username');
        $data->email = $request->input('email');
        $data->password = app('hash')->make($request->input('password'));

        $data->save();

        return response()->json([
            'code' => 200,
            'message' => 'successufully update data admin',
            'data' => $data]);
    }

    public function delete($id)
    {
        $data = User::find($id);
        if (!$data) {
            return $this->notFound();
        }

        $data->delete();

        return response()->json([
            'code' => 200,
            'message' => 'successufully delete data',
            'data' => []]);
    }

    public function notFound() {
        return response()->json([
            'code' => 404,
            'message' => 'data admin not found',
            'data' => ''], 404);
    }

    public function cobaPublisher() {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new \PhpAmqpLib\Message\AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        $channel->close();
        $connection->close();
    }

    public function reciver() {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}

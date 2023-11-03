<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AdminController extends Controller
{
    private $connection;
    private $channel;
    
    public function __construct() {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $this->channel = $this->connection->channel();
    }
    public function __destruct() {
        $this->channel->close();
        $this->connection->close();
    }
    
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
        $msg = new AMQPMessage('Hello World coba lagi jam 4!');
        $this->channel->exchange_declare(env('RABBITMQ_EXCHANGE'), 'direct', false, false, false);
        $this->channel->basic_publish($msg, env('RABBITMQ_EXCHANGE'), env('RABBITMQ_ROUTING_KEY'));
        
        return response()->json([
            'code' => 200,
            'message' => 'successufully publish data',
            'data' => []]);
    }

    public function reciver() {

    }
}

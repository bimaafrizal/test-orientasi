<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use MongoDB\Client;

class ExampleController extends Controller
{
    private $mongoDB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $mongoDB)
    {
        $this->mongoDB = $mongoDB;
    }

    public function test() {
        $data = User::all();
        return response()->json([
            'message' => 'Hello World!',
            'data' => $data
        ], 200);
    }

    public function index() {
        try {
            $this->mongoDB->listDatabases(); // This will check the connection to the MongoDB server
            return response()->json(['message' => 'MongoDB connection successful'], 200);
        } catch (ConnectionException $exception) {
            return response()->json(['error' => 'MongoDB connection failed: ' . $exception->getMessage()], 500);
        }
    }

    public function store(){
        $user =new User();
        $user->name = 'John Doe';
        $user->email = 'bimaafrizalmalna@gmail.com';

        $user->save();
        return response()->json([
            'message' => 'User Created',
            'data' => $user
        ], 200);
    }
}

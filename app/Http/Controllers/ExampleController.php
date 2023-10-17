<?php

namespace App\Http\Controllers;

use App\Models\Datas;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $blogs = Datas::all();
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' =>$blogs
        ]);
    }
}

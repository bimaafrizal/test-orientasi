<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            'code' => 200,
            'message' => 'successufully create one admin',
            'data' => $data]);

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
            return response()->json([
                'code' => 404,
                'message' => 'data not found',
                'data' => '']);
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
            'data' => '']);
    }
}

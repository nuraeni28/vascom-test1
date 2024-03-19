<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric|digits_between:8,14',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $request->all();
        $data['password'] = app('hash')->make($request->password); // Hashing password
        $user = User::create($data);

        return response()->json(['code' => 201, 'message' => 'User created successfully', 'data' => $user, 'token' => $user->createToken('users')->accessToken]);
    }
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($query) use ($searchTerm) {
                $query
                    ->where('name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%")
                    ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);

        $users = $query->take($take)->skip($skip)->get();

        return response()->json([
            'code' => 200,
            'message' => 'User list',
            'data' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['code' => 404, 'message' => 'User not found', 'data' => $user]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric|digits_between:8,14',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $request->all();
        $data['password'] = app('hash')->make($request->password);
        $user->fill($data);
        $user->save();

        return response()->json(['code' => 200, 'message' => 'User updated successfully', 'data' => $user]);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['code' => 404, 'message' => 'User not found', 'data' => $user]);
        }

        $user->delete();

        return response()->json(['code' => 200, 'message' => 'Data deleted successfully', 'data' => $user]);
    }
}

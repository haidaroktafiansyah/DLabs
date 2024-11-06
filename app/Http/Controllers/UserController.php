<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserController extends Controller
{
    private function getValidationRules()
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'age' => 'required|integer|min:18',
            'password' => 'required',
            'status' => 'required',
        ];
    }

    private function emailExists($email, $excludeId = null)
    {

        $query = DB::table('users')->where('email', $email);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function get(Request $request)
    {
        try {

            $count = $request->count ?: 10;
            $sortBy = $request->sort_by ?: 'id';
            $sortOrder = $request->sort_order ?: 'asc';

            $users = DB::table('users')
                ->leftJoin('roles', 'users.status', '=', 'roles.code')
                ->select('users.*', 'roles.code as role_code', 'roles.privileges as role_privileges')
                ->orderBy($sortBy, $sortOrder)
                ->paginate($count)
                ->toArray();
                
            unset($users['links']);

            return response()->json($users, 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getById(string $id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();

            if (!$user) {
                return response()->json(['error' => ['id' => ['User not found.']]], 404);
            }

            return response()->json(['data' => $user], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function post(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->getValidationRules());

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            if ($this->emailExists($request->email)) {
                return response()->json(['error' => ['email' => ['The email is already taken.']]], 400);
            }

            $userData = $request->only(['name', 'email', 'age', 'status']);
            $userData['password'] = Hash::make($request->password);

            $user = User::create($userData);

            return response()->json(['msg' => 'Successfully added data', 'data' => $user], 201);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function put(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), $this->getValidationRules());

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = DB::table('users')->where('id', $id)->first();
            if (!$user) {
                return response()->json(['error' => ['id' => ['Invalid id.']]], 404);
            }

            if ($this->emailExists($request->email, $id)) {
                return response()->json(['error' => ['email' => ['The email is already taken.']]], 400);
            }

            $userData = $request->only(['name', 'email', 'age', 'status']);
            $userData['password'] = Hash::make($request->password);

            DB::table('users')->where('id', $id)->update($userData);

            return response()->json(['msg' => 'Successfully updated data', 'data' => $userData], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function delete(string $id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();

            if (!$user) {
                return response()->json(['error' => ['id' => ['Invalid id.']]], 404);
            }

            DB::table('users')->where('id', $id)->delete();

            return response()->json(['msg' => 'Successfully deleted data', 'data' => $user], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}

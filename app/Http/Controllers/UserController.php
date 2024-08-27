<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Userid;

class UserController extends Controller
{
    //


    public function guestUserCreate(Request $request)
    {
        try {
            $validated = $request->validate(["user_id" => "required"]);
            $userid = Userid::where('user_id', $validated['user_id'])->get();
            if ($userid) {
                Userid::create($validated);
                return response()->json(["status" => 200, "message" => "Registration Success"], 200);

            }

        } catch (ValidationException $e) {
            return response()->json(["status" => 401, "message" => $e->errors()], 401);
        } catch (Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }
    public function createUser(Request $request)
    {
        try {
            $validated = $request->validate(["user_id" => "required", "name" => 'required|alpha', "password" => "required|min:8", "email" => "required|email"]);
            $data = [...$validated, "password" => Hash::make($validated["password"])];
            $user = User::create($data);
            Userid::where('user_id', $validated['user_id'])->update(['user_status' => 2]);
            Auth::login($user);
            return response()->json(["status" => 200, "message" => "Registration Success", "username" => Auth::user()->name, "userid" => Auth::user()->user_id], 200);
        } catch (ValidationException $e) {
            return response()->json(["status" => 401, "message" => $e->errors()], 401);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Duplicate entry: the email already exists'
                ], 400);
            }
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }

    public function logoutUser()
    {
        try {
            Auth::logout();
            return response()->json(["status" => 200, "message" => "Logged Out SuccessFully !"], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validated = $request->validate(["email" => "required|email", "password" => "required|min:8"]);
            if (!Auth::attempt(["email" => $validated['email'], "password" => $validated['password']])) {
                return response()->json(["status" => 401, "message" => "Invalid Credentials"], 401);
            }
            return response()->json(["status" => 200, "message" => "Logged in", "username" => Auth::user()->name, "userid" => Auth::user()->user_id], 200);
        } catch (ValidationException $e) {
            return response()->json(["status" => 400, "message" => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }



}


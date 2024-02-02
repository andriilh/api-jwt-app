<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function sendResponse($data, $message, $status = 200)
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function register(Request $request)
    {
        $input = $request->only('name', 'email', 'password', 'password_confirmation');

        $validator = Validator::make($input, [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }

        $input['password'] = bcrypt($input['password']); // use bcrypt to hash the passwords
        $user = User::create($input); // eloquent creation of data

        $success['user'] = $user;

        return $this->sendResponse($success, 'user registered successfully', 201);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }

        try {
            // this authenticates the user details with the database and generates a token
            if (!$token = JWTAuth::attempt($input)) {
                return $this->sendError([], "invalid login credentials", 401);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }

        $success = [
            'token' => $token,
            'token_type' => 'bearer'
        ];
        return $this->sendResponse($success, 'successful login', 200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->sendResponse([], 'Successfully logged out', 200);
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }
    }

    public function getUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }

        return $this->sendResponse($user, "user data retrieved", 200);
    }

    public function refresh()
    {
        return $this->sendResponse([
            'token' => JWTAuth::refresh(),
            'token_type' => 'bearer',
        ], 'refresh token', 200);
    }
}

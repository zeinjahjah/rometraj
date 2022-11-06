<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required | string | min:3',
            'email' => 'required | string | unique:users,email',
            'password' => 'required | string | confirmed',
            'phone'      => 'string',
            'type'      => 'required | string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'type' => $fields['type'],
            'password' => bcrypt($fields['password'])
        ]);

        $response = $user;
        $token = $user->createToken('mapptoken')->plainTextToken;
        $response['token'] = $token;
        $response['status'] = 1;

        return response($response, 201);
    }

    public function logout(Request $request)
    {

        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        PersonalAccessToken::destroy($token->id);
        return [
            'status' => 1,
            'message' => 'Logged out'
        ];
    }


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required | string ',
            'password' => 'required | string '
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first();

        // check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'status' => 0,
                'message' => 'Bad Creds'

            ], 401);
        }


        $token = $user->createToken('mapptoken')->plainTextToken;

        $response = $user;
        $response['token'] = $token;
        $response['status'] = 1;

        return response($response, 201);
    }
}

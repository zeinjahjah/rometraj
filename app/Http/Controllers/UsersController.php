<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use App\Models\ToWatch;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can add films
        if ($user->type == 'admin') {
            $data = User::where("type", $type)->get();
            return response([
                'status' => 0,
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can edit users or the same user
        if ($user->type == 'admin' || $user->id == $id) {
            $user =  User::find($id);
            $user->update($request->all());
            return response([
                'status' => 0,
                'data' => $user
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can edit films or the same user
        if ($user->type == 'admin' || $user->id == $id) {

            return response([
                'status' => 0,
                'data' => User::destroy($id)
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * get history of a user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getHistory(Request $request, $user_id)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can edit films or the same user
        if ($user->id == $user_id) {
            $data = History::where('user_id', $user_id)->get();
            return response([
                'status' => 0,
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * add a film to theuser history list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AddHistory(Request $request, $user_id, $film_id)
    {
        $inputs = [
            'user_id' => $user_id,
            'film_id' => $film_id
        ];
        return response([
            'status' => 0,
            'data' =>  History::create($inputs)

        ], 200);
    }


    /**
     * get to watch of a user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getToWatch(Request $request, $user_id)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can edit films or the same user
        if ($user->id == $user_id) {
            $data = ToWatch::where('user_id', $user_id)->get();
            return response([
                'status' => 0,
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * add a to watch to the user ToWatch list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AddToWatch(Request $request, $user_id, $film_id)
    {
        $inputs = [
            'user_id' => $user_id,
            'film_id' => $film_id
        ];
        return response([
            'status' => 0,
            'data' =>  ToWatch::create($inputs)

        ], 200);
    }



    /**
     * add a to watch to the user ToWatch list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteToWatch(Request $request, $user_id, $film_id)
    {
        $inputs = [
            'user_id' => $user_id,
            'film_id' => $film_id
        ];
        return response([
            'status' => 0,
            'data' =>  ToWatch::create($inputs)

        ], 200);
    }
}

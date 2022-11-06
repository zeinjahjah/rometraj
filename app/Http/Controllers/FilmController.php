<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\ToWatch;
use App\Models\Rating;
use Laravel\Sanctum\PersonalAccessToken;


class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response([
            'status' => 1,
            'data' => Film::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        // only admin can add films
        if ($user->type == 'admin') {
            $request->validate([
                'name' => 'required | min:1',
                'description' => 'min:10',
                'category' => 'required | min:1',
                'publish_year' => 'digits:4|integer|min:1900',
                'video_url' => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'photo_url' => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'director' => 'min:3',
                'actors' => 'array|min:3'
            ]);

            $film =  Film::create($request->all());
            return response([
                'status' => 1,
                'data' => $film
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response([
            'status' => 1,
            'data' => Film::find($id)
        ], 200);
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

        // only admin can edit films
        if ($user->type == 'admin') {
            $film =  Film::find($id);
            $film->update($request->all());
            return response([
                'status' => 1,
                'data' => $film
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

        // only admin can edit films
        if ($user->type == 'admin') {

            return response([
                'status' => 1,
                'data' => Film::destroy($id)
            ], 200);
        } else {
            return response([
                'message' => 'Don\'t have enough permession.',
                'status' => 0
            ], 401);
        }
    }

    /**
     * search for film
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $search = Film::where('name', 'like', '%' . $name . '%')->get();
        return response([
            'status' => 1,
            'data' => $search
        ], 200);
    }

    /**
     * get rate for film
     *
     * @param  int  $film_id
     * @return \Illuminate\Http\Response
     */
    public function getRating($film_id)
    {
        $rates = Rating::where('film_id', $film_id)->get();
        $rate_1 = $rates->filter(function ($rate) {
            return $rate->rate == 1;
        })->count();
        $rate_2 = $rates->filter(function ($rate) {
            return $rate->rate == 2;
        })->count();
        $rate_3 = $rates->filter(function ($rate) {
            return $rate->rate == 3;
        })->count();
        $rate_4 = $rates->filter(function ($rate) {
            return $rate->rate == 4;
        })->count();
        $rate_5 = $rates->filter(function ($rate) {
            return $rate->rate == 5;
        })->count();

        $ratesArr = array(
            1 => $rate_1,
            2 => $rate_2,
            3 => $rate_3,
            4 => $rate_4,
            5 => $rate_5,
        );

        $max = 0;
        $n = 0;
        foreach ($ratesArr as $rate => $count) {
            $max += $rate * $count;
            $n += $count;
        }
        $rate_result = $max / $n;

        return response([
            'status' => 1,
            'data' => ['rate' => number_format($rate_result, 2)]
        ], 200);
    }


    /**
     * add rate for film
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function addRating(Request $request, $film_id, $rate)
    {
        $bearerToken = $request->bearerToken();
        $token       = PersonalAccessToken::findToken($bearerToken);
        $user        = $token->tokenable;

        $input = [
            'user_id' => $user->id,
            'film_id' => $film_id,
            'rate' => $rate
        ];

        return response([
            'status' => 1,
            'data' => Rating::create($input)
        ], 200);
    }
}

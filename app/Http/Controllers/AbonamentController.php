<?php

namespace App\Http\Controllers;

use App\Models\Abonament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AbonamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'status' => 1,
            'data' => Abonament::all()
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
        $inputs = $request->all();

        $inputs['start_date'] = date($inputs['start_date']);
        $inputs['end_date'] = date($inputs['end_date']);
        // echo json_encode($inputs);die;

        $abonament =  Abonament::create($inputs);
        return response([
            'status' => 1,
            'data' => $abonament
        ], 200);
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
            'data' => Abonament::find($id)
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
        $abonament =  Abonament::find($id);
        $abonament->update($request->all());
        return response([
            'status' => 1,
            'data' => $abonament
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response([
            'status' => 1,
            'data' => Abonament::destroy($id)
        ], 200);
    }
}

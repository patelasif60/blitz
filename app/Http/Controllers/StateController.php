<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        //
    }

    public function getDropdownData(Request $request){
        $data = $request->refarance == 'provincesId'? getStateCountryWise($request->id) : getCityStateWise($request->id); ;
        if(!empty($data)) {
                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong']);

    }

    /* Get Country Name By State. */

    public function getStateByCountry(Request $request, $id) {
        if ($request->ajax()) {
            $states = State::where('country_id', $id)->select(['id', 'name'])->get();
            if (!empty($states)) {
                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => $states]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong']);
    }
}

<?php

namespace App\Http\Controllers\Buyer\Permissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * User has permission or not
     * Purpose- User can use by using Ajax
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hasPermission(Request $request)
    {
        try {

            if (isset($request->param) && !empty($request->param)) {
                 if (Auth::user()->hasPermissionTo($request->param)) {
                     return response()->json(['success' => true, 'message' => __('admin.access_granted')],200);
                 }

                Log::alert('Code - 403 | ErrorCode:B029 User Permission');
                return response()->json(['success' => false, 'message' => __('admin.user_dont_have_permission')],403);
            }

            Log::critical('Code - 400 | ErrorCode:B029 User Permission');
            return response()->json(['success' => false, 'message' => __('admin.bad_request')],400);

        } catch (\Exception $exception) {

            Log::critical('Code - 500 | ErrorCode:B029 User Permission');
            return response()->json(['success' => false, 'message' => __('admin.bad_request')],500);

        }
    }
}

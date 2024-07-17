<?php

namespace App\Http\Controllers;

use App\Models\PermissionsGroup;
use Illuminate\Http\Request;
use Log;

class PermissionsGroupController extends Controller
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
     * @param  \App\Models\PermissionsGroup  $permissionsGroup
     * @return \Illuminate\Http\Response
     */
    public function show(PermissionsGroup $permissionsGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PermissionsGroup  $permissionsGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionsGroup $permissionsGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PermissionsGroup  $permissionsGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermissionsGroup $permissionsGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PermissionsGroup  $permissionsGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionsGroup $permissionsGroup)
    {
        //
    }

    /**
     * Get dependent permissions of a permission / module
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDependencyPermissions(Request $request)
    {
        try {
            if (!empty($request->id)) {
                $permissionGroup = PermissionsGroup::find($request->id);

                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => json_decode($permissionGroup->related_permissions)],200);
            }

            Log::critical('Code - 400 | ErrorCode:B030 Dependency Permission');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')], 400);

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B030 Dependency Permission');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')], 500);
        }
    }
}

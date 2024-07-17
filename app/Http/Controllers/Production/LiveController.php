<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Log;

class LiveController extends Controller
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
     *
     * Only run on live environment
     *
     * @param $dir
     * @param array $results\
     */
    public function removeHtaccess()
    {
        try {

            $directories = ['assets','buyer-blitznet-invoice-pdf','buyer-order-pdf','chat','css','front-assets','home_design','images','js','new_design','order-pdf','uploads'];

            foreach ($directories as $directory)
            {
                $allFiles = Storage::disk('public1')->allFiles($directory);

                Log::info($directory.' | '. json_encode($allFiles));

                $files = array_filter(Storage::disk('public1')->allFiles($directory), function ($item) {
                    return strpos($item, '.htaccess');
                });

                Log::info('DELETED FILES FROM '.$directory.' | '. json_encode($files));

                foreach ($files as $file) {
                    Storage::disk('public1')->delete($file);
                }
            }

            return response()->json(['success' => true, 'message' => 'Process completed !!']);


        } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => 'Something went wrong !!']);

        }
    }
}

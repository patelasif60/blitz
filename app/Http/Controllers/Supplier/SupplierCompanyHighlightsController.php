<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\Supplier\SupplierProfessionalProfileRequest;
use App\Models\CompanyHighlights;
use App\Models\CompanyUserType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SupplierCompanyHighlightsController extends Controller
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
    public function store(SupplierProfessionalProfileRequest $request, $id)
    {
        $supplier = Supplier::with(['user','user.company'])->where('id',Crypt::decrypt($id))->first();
        $highlightFilePath = $this->uploadHighlightFiles($request);

        CompanyHighlights::create([
            'user_id'               => isset($supplier->user->id) ? $supplier->user->id : null,
            'model_type'            => Supplier::class,
            'model_id'              => isset($supplier->id) ? $supplier->id : null,
            'company_id'            => isset($supplier->user->company->id) ?$supplier->user->company->id : '',
            'category'              => $request->category,
            'name'                  => $request->name,
            'number'                => $request->number,
            'image'                 => $highlightFilePath
        ]);

        CompanyHighlights::bootSystemActivities();

        return response()->json(['success' => true, 'message' => __('admin.company_achievement_added')]);



        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);
    }

    /**
     * Upload files of Company members
     *
     * @param $request
     * @return string
     */
    public function uploadHighlightFiles($request)
    {
        $filePath = '';
        $companyHighlights = new CompanyHighlights;
        if ($request->file('highlightImage')) {
            $filePath = $companyHighlights->uploadOne($request->file('highlightImage'),'uploads/supplier','public','highlight_');
        }else{
            $filePath = $request->oldhighlightImage;
        }
        return $filePath;
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
        /*try {*/
        if(!empty($id)){
            $id = Crypt::decrypt($id);
            $data = CompanyHighlights::find($id);
            $data['highlight_image'] = '';

            if(!empty($data->image)) {
                    $image_name = substr(Str::substr($data->image, stripos($data->image, 'highlight_') + 9), 0, -4);
                    $data['highlight_image'] = $image_name;
            }
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'),'data' => json_decode($data)]);
        }

        /*} catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],500);
        }*/

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],400);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierProfessionalProfileRequest $request, $id)
    {
        try {
            $supplier = Supplier::with(['user','user.company'])->where('id',$request->userId)->first();
            //dd($request->all());
            $highlightFilePath = $this->uploadHighlightFiles($request);


            CompanyHighlights::where('id',Crypt::decrypt($id))->update([
                'user_id'               => isset($supplier->user->id) ? $supplier->user->id : null,
                'model_type'            => Supplier::class,
                'model_id'              => isset($supplier->id) ? $supplier->id : null,
                'company_id'            => isset($supplier->user->company->id) ?$supplier->user->company->id : '',
                'category'              => $request->category,
                'name'                  => $request->name,
                'number'                => $request->number,
                'image'                 => $highlightFilePath
            ]);

            CompanyHighlights::bootSystemActivities();

            return response()->json(['success' => true, 'message' => __('admin.company_achievement_updated')]);

        } catch (\Exception $exception) {

            return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],500);
        }

        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);

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
     * Get company highlights
     *
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request)
    {
        if ($request->ajax()) {

            $id = $request->id;
            $data = [];
            if (!empty($id)) {
                $data = CompanyHighlights::find($id);
                $data['highlight_image'] = '';

                if (!empty($data->image)) {
                    $highlight_image_name = substr(Str::substr($data->image, stripos($data->image, 'highlight_') + 10), 0, -4);
                    $data['highlight_image'] = $highlight_image_name;
                }
                return json_decode($data);
            }

            if ($request->ajax()) {
                if (!empty($request->supplier_id)) {
                    $data = CompanyHighlights::select('id', 'category', 'name', 'number', 'image')->where('is_deleted', 0)
                        ->where('model_id', $request->supplier_id)->where('model_type', Supplier::class)
                        ->orderBy('id', 'DESC')->get();
                }
                $table = Datatables::of($data)->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $action = '<a href="javascript:void(0)" class="show-icon ps-2 editHighlight" data-id="' . Crypt::encrypt($row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#Highlights"><i class="fa fa-edit"></i></a>';
                        $action .= '<a href="javascript:void(0)" id="delete" class="show-icon ps-2 deleteHighlight" data-id="' . Crypt::encrypt($row->id). '" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                        return $action;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                return $table;
            }
        }



    }

    public function deleteOrDownloadImages(Request $request)
    {
        $id = $request->id;
        $data = CompanyHighlights::find($id);
        $fileName = $data->image;
        if($data) {
            if($request->type == "download"){
                ob_end_clean();
                $headers = array('Content-Type: image/*, application/pdf');
                return Storage::download('/public/' . $fileName, '', $headers);
            }else{
                $data->image = NULL;
                $data->save();
                Storage::delete('/public/' . $fileName);
                return true;
            }
        }
    }
}

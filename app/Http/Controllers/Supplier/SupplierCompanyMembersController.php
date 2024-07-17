<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\Supplier\SupplierProfessionalProfileRequest;
use App\Models\CompanyMembers;
use App\Models\CompanyUserType;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SupplierCompanyMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
            $memberFilePath = $this->uploadMemberFiles($request);

            $userType = CompanyUserType::find($request->company_user_type_id)->user_type;

            CompanyMembers::create([
                'user_id'               => isset($supplier->user->id) ? $supplier->user->id : null,
                'model_type'            => Supplier::class,
                'model_id'              => isset($supplier->id) ? $supplier->id : null,
                'company_id'            => isset($supplier->user->company->id) ?$supplier->user->company->id : '',
                'company_user_type_id'  => $request->company_user_type_id,
                'salutation'            => $request->salutation,
                'firstname'             => $request->firstname,
                'lastname'              => $request->lastname,
                'email'                 => $request->email,
                'country_phone_code'    => $request->country_phone_code,
                'phone'                 => $request->phone,
                'designation'           => $request->designation,
                'position'              => $request->position,
                'sector'                => $request->sector,
                'registration_NIB'      => $request->registration_NIB,
                'portfolio_type'        => $request->portfolio_type,
                'company_name'          => $request->company_name,
                'quote'                 => $request->quote,
                'image'                 => $memberFilePath,
                'description'           => strip_tags($request->description),
            ]);

            CompanyMembers::bootSystemActivities();

            $idMsg = "";
            if($request->company_user_type_id == "1"){
                $idMsg = __('admin.core_team_detail');
            }else if($request->company_user_type_id == "2"){
                $idMsg = __('admin.testimonial');
            }else if($request->company_user_type_id == "3"){
                $idMsg = __('admin.partner');
            }else if($request->company_user_type_id == "4"){
                $idMsg = __('admin.client_portfolio');
            }

            return response()->json(['success' => true, 'message' => __('admin.member_added',['id' => $idMsg])]);



        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);
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
                $data = CompanyMembers::find($id);
                $data['member_image'] = '';

                if(!empty($data->image)){
                    if($data->company_user_type_id == 1){
                        $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'coreteam_') + 9), 0, -4);
                    }elseif ($data->company_user_type_id == 2){
                        $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'testimonial_') + 12), 0, -4);
                    }elseif ($data->company_user_type_id == 3){
                        $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'partner_') + 8), 0, -4);
                    }elseif ($data->company_user_type_id == 4){
                        $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'portfolio_') + 10), 0, -4);
                    }

                    $data['member_image'] = $member_image_name;
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
            $memberFilePath = $this->uploadMemberFiles($request);

            $userType = CompanyUserType::find($request->company_user_type_id)->user_type;

            CompanyMembers::where('id',Crypt::decrypt($id))->update([
                'company_id'            => isset($supplier->user->company->id) ? $supplier->user->company->id : '',
                'company_user_type_id'  => $request->company_user_type_id,
                'salutation'            => $request->salutation,
                'firstname'             => $request->firstname,
                'lastname'              => $request->lastname,
                'email'                 => $request->email,
                'country_phone_code'    => $request->country_phone_code,
                'phone'                 => $request->phone,
                'designation'           => $request->designation,
                'position'              => $request->position,
                'sector'                => $request->sector,
                'registration_NIB'      => $request->registration_NIB,
                'portfolio_type'        => $request->portfolio_type,
                'company_name'          => $request->company_name,
                'quote'                 => $request->quote,
                'image'                 => $memberFilePath,
                'description'           => strip_tags($request->description),
            ]);

            CompanyMembers::bootSystemActivities();
            $idMsg = "";
            if($request->company_user_type_id == "1"){
                $idMsg = __('admin.core_team_detail');
            }else if($request->company_user_type_id == "2"){
                $idMsg = __('admin.testimonial');
            }else if($request->company_user_type_id == "3"){
                $idMsg = __('admin.partner');
            }else if($request->company_user_type_id == "4"){
                $idMsg = __('admin.client_portfolio');
            }

            return response()->json(['success' => true, 'message' => __('admin.member_updated',['id' => $idMsg])]);

        } catch (\Exception $exception) {

            return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],500);
        }

        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);
    }

    /**
     * Upload files of Company members
     *
     * @param $request
     * @return string
     */
    public function uploadMemberFiles($request)
    {
        $filePath = '';
        $companyMembers = new CompanyMembers;
        if ($request->file('coreTeamImage')) {
            $filePath = $companyMembers->uploadOne($request->file('coreTeamImage'),'uploads/supplier','public','coreteam_');

        }else if ($request->file('testimonialImage')) {
            $filePath = $companyMembers->uploadOne($request->file('testimonialImage'),'uploads/supplier','public','testimonial_');

        }else if ($request->file('partnerImage')) {
            $filePath = $companyMembers->uploadOne($request->file('partnerImage'),'uploads/supplier','public','partner_');

        }else if ($request->file('portfolioImage')) {
            $filePath = $companyMembers->uploadOne($request->file('portfolioImage'),'uploads/supplier','public','portfolio_');

        }else{
            $filePath = $request->oldmemberImage;
        }

        return $filePath;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

        } catch (\Exception $exception) {

        }
    }

    /**
     * Get Company Members data
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getList(Request $request)
    {
        $id = $request->id;

        if($request->company_user_type == 1){
            $editClass = 'editCoreTeam';
            $deleteClass = 'deleteCoreTeam';
        }else if($request->company_user_type == 2){
            $editClass = 'editTestimonial';
            $deleteClass = 'deleteTestimonial';
        }else if($request->company_user_type == 3){
            $editClass = 'editPartner';
            $deleteClass = 'deletePartner';
        }else if($request->company_user_type == 4){
            $editClass = 'editPortfolio';
            $deleteClass = 'deletePortfolio';
        }
        if(!empty($id)){
            $data = CompanyMembers::find($id);
            $data['member_image'] = '';

            if(!empty($data->image)){
                if($data->company_user_type_id == 1){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'coreteam_') + 9), 0, -4);
                }elseif ($data->company_user_type_id == 2){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'testimonial_') + 12), 0, -4);
                }elseif ($data->company_user_type_id == 3){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'partner_') + 8), 0, -4);
                }elseif ($data->company_user_type_id == 4){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'portfolio_') + 10), 0, -4);
                }

                $data['member_image'] = $member_image_name;
            }
            return json_decode($data);
        }

        if ($request->ajax()) {

            $data = [];

            if (!empty($request->supplier_id)) {
                $data = CompanyMembers::select('id','salutation','firstname','lastname','email','phone','designation','position','sector','registration_NIB','portfolio_type','company_name','image','description')->where('is_deleted',0)->where('company_user_type_id',$request->company_user_type)->where('model_id',$request->supplier_id)->orderBy('id','DESC')->get();
            }

            $table = Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) use ($editClass,$deleteClass){
                    $btn = '<a href="javascript:void(0)" class="show-icon ps-2 '.$editClass.'" data-id="'.Crypt::encrypt($row->id).'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" class="show-icon ps-2 '.$deleteClass.'" data-id="'.Crypt::encrypt($row->id).'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

            return $table;
        }
    }

    public function deleteOrDownloadImages(Request $request)
    {
        $imageType = $request->imageType;
        if($imageType == "coreTeam"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "testimonial"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "partner"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "portfolio"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }

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

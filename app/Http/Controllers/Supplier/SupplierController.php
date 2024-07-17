<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\Supplier\CompanyDetailsRequest;
use App\Http\Requests\Supplier\SupplierProfessionalProfileRequest;
use App\Models\AvailableBank;
use App\Models\CompanyAddress;
use App\Models\CompanyDetails;
use App\Models\Settings;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\SuppliersBank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\SupplierDealWithCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use View;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $authUser = Auth::user()->load('supplier');
            $supplier = $authUser->supplier;
            $userId = Crypt::encrypt($authUser->id);
            $pkpNonpkp = [];
            $banks = AvailableBank::where('can_disburse', 1)->get()->toArray();
            $supplierBanks = SuppliersBank::where('supplier_id', $supplier->id, 'delete')->get();
            $companyDetails = CompanyDetails::where('user_id', $authUser->id)->first();
            $supplierAddress = CompanyAddress::where('model_id', $supplier->id)->get()->toArray();

            /** Supplier Dealing With category */
            $supplierId = $supplier->id;
            $dealing_category = Supplier::dealWithSubCategoriesTag($supplierId);
            $categories = json_encode($dealing_category);

            /** Supplier Dealing With category */


            /** begin: PKP Non PKP Files */
            $pkpFileTitle = $extension_pkp_file = $pkp_file_filename = $pkp_file_name = '';

            if ($supplier->company_type == 1 && $supplier->pkp_file) {
                $pkpFileTitle = Str::substr($supplier->pkp_file, stripos($supplier->pkp_file, "pkp_file_") + 10);
                $extension_pkp_file = getFileExtension($pkpFileTitle);
                $pkp_file_filename = getFileName($pkpFileTitle);
                if (strlen($pkp_file_filename) > 10) {
                    $pkp_file_name = substr($pkp_file_filename, 0, 10) . '...' . $extension_pkp_file;
                } else {
                    $pkp_file_name = $pkp_file_filename . $extension_pkp_file;
                }
            }
            $pkpNonpkp = ['pkpFileTitle' => $pkpFileTitle, 'extension_pkp_file' => $extension_pkp_file, 'pkp_file_filename' => $pkp_file_filename, 'pkp_file_name' => $pkp_file_name];
            /** end: PKP Non PKP Files */
            $slug_prefix = Settings::where('key', 'slug_prefix')->first()->value;
            return view('supplier.profile.supplierProfessionalProfile', compact(['pkpNonpkp', 'banks', 'supplierBanks', 'companyDetails', 'supplier', 'supplierAddress', 'userId', 'authUser', 'slug_prefix', 'categories']));

        } catch (\Exception $exception) {
            abort('404');
        }
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyDetailsRequest $request, $id)
    {
        try {
            $authUser = User::with(['supplier', 'company'])->where('id', Crypt::decrypt($id))->first();
            $supplier = Supplier::find($authUser->supplier->id);
            $supplier = $this->uploadCompanyDetailsFiles($request, $supplier);

            /**begin: PKP & Non PKP */

            /**end: PKP & Non PKP */

            $supplier->name = trim($request->name);
            $supplier->email = $request->email;
            $supplier->c_phone_code = $request->c_phone_code ? '+' . $request->c_phone_code : '';
            $supplier->mobile = $request->mobile;
            $supplier->website = $request->website;
            $supplier->nib = $request->nib;
            $supplier->npwp = $request->npwp;
            $supplier->group_margin = $request->group_margin;
            $supplier->salutation = $request->salutation;
            $supplier->contact_person_name = $request->contactPersonName;
            $supplier->contact_person_last_name = $request->contactPersonLastName;
            $supplier->contact_person_email = trim($request->contactPersonEmail);
            $supplier->alternate_email = $request->alternate_email ? trim($request->alternate_email) : '';
            $supplier->licence = $request->licence;
            $supplier->company_alternative_phone_code = $request->company_alternative_phone_code?'+'.$request->company_alternative_phone_code:'';
            $supplier->company_alternative_phone = $request->company_alternative_phone;
            $supplier->facebook = $request->facebook;
            $supplier->twitter = $request->twitter;
            $supplier->linkedin = $request->linkedin;
            $supplier->youtube = $request->youtube;
            $supplier->instagram = $request->instagram;
            $supplier->profile_username = Str::replace(' ', '-', $request->profile_username);
            $supplier->established_date = !empty($request->established_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->established_date)->format('Y-m-d') : '';
            $supplier->updated_by = Auth::id();
            $supplier->interested_in = $request->interested_in ?? '';
            $supplier->company_type = $request->companyType;
            $supplier->save();

            /**begin: Update supplier company location address and remove previous added addresss*/
            CompanyAddress::where('model_id', $supplier->id)->delete();
            if (!empty($request->address)) {
                foreach ($request->address as $key => $address) {
                    if ($request->address[$key]) {
                        $supplierCompany = new CompanyAddress;
                        $supplierCompany->model_type = Supplier::class;
                        $supplierCompany->model_id = $supplier->id;
                        $supplierCompany->user_id = $authUser->id; // get user id by supplier id
                        $supplierCompany->address = $request->address[$key];
                        $supplierCompany->company_id = null;
                        $supplierCompany->is_deleted = 0;
                        $supplierCompany->save();

                        /**begin: system log**/
                        $supplierCompany->bootSystemActivities();
                        /**end: system log**/
                    }
                }
            }
            /**end: Update supplier company location address and remove previous added addresss*/


            /**begin: Update User**/
            if (!empty($authUser->id)) {
                $user = User::find($authUser->id);
                $user->salutation = $request->salutation;
                $user->firstname = $request->contactPersonName;
                $user->lastname = $request->contactPersonLastName;
                $user->email = trim($request->contactPersonEmail);
                $user->save();

                /**begin: system log**/
                $user->bootSystemActivities();
                /**end: system log**/
            }
            /**end: Update User**/

            $needChangeXenCompanyName = false;
            if (!empty($supplier->xen_platform_id) && $supplier->name != $supplier->xenAccount()->value('business_name')) {
                $needChangeXenCompanyName = true;
            }

            /**begin: system log**/
            $supplier->bootSystemActivities();
            /**end: system log**/

            return response()->json(array('success' => true, 'supplierId' => $supplier->id, 'changeXenCompany' => $needChangeXenCompanyName));

        } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')], 500);
        }

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')], 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update Supplier Company Details Files
     *
     * @param $request
     * @param $supplier
     * @return mixed
     */
    public function uploadCompanyDetailsFiles($request, $supplier)
    {
        if ($request->file('logo')) {
            $supplier->removeOne($supplier->logo);
            $supplier->logo = $supplier->uploadOne($request->file('logo'), 'uploads/supplier', 'public', 'logo_');
        }

        if ($request->file('catalog')) {
            $supplier->removeOne($supplier->catalog);
            $supplier->catalog = $supplier->uploadOne($request->file('catalog'), 'uploads/supplier', 'public', 'catalog_');
        }

        if ($request->file('pricing')) {
            $supplier->removeOne($supplier->pricing);
            $supplier->pricing = $supplier->uploadOne($request->file('pricing'), 'uploads/supplier', 'public', 'pricing_');
        }

        if ($request->file('product')) {
            $supplier->removeOne($supplier->product);
            $supplier->product = $supplier->uploadOne($request->file('product'), 'uploads/supplier', 'public', 'product_');
        }

        if ($request->file('commercialCondition')) {
            $supplier->removeOne($supplier->commercialCondition);
            $supplier->commercialCondition = $supplier->uploadOne($request->file('commercialCondition'), 'uploads/supplier', 'public', 'termsconditions_file_');
        }

        if ($request->file('nib_file')) {
            $supplier->removeOne($supplier->nib_file);
            $supplier->nib_file = $supplier->uploadOne($request->file('nib_file'), 'uploads/supplier', 'public', 'nib_file_');
        }

        if ($request->file('npwp_file')) {
            $supplier->removeOne($supplier->npwp_file);
            $supplier->npwp_file = $supplier->uploadOne($request->file('npwp_file'), 'uploads/supplier', 'public', 'npwp_file_');
        }
        /**begin: PKP & Non PKP */
        if ($request->companyType == 1 && $request->file('pkp_file')) {
            if ($supplier->pkp_file) {
                Storage::delete('/public/' . $supplier->pkp_file);
            }
            $pkpFileName = Str::random(10) . '_' . time() . 'pkp_file_' . $request->file('pkp_file')->getClientOriginalName();
            $pkpFilePath = $request->file('pkp_file')->storeAs('uploads/supplier', $pkpFileName, 'public');
            $supplier->pkp_file = $pkpFilePath;
        }
        if ($request->companyType == 2) {
            if ($supplier->pkp_file) {
                Storage::delete('/public/' . $supplier->pkp_file);
            }
            $supplier->pkp_file = '';
        }
        /**end: PKP & Non PKP */

        return $supplier;
    }

    /**
     * Supplier profile view
     */
    public function supplierProfile($username)
    {
        try {
            if (Str::startsWith($username, getSettingValueByKey('slug_prefix'))) {
                $username = Str::substr($username, 6);
                $supplierId = Supplier::where('profile_username', $username)->first()->id;
                $supplier = Supplier::with(['companyDetails', 'companyHighlights', 'companyMembers', 'user', 'supplierProducts', 'supplierProducts.product', 'supplierProducts.productImage', 'supplierProducts.productDiscountRange', 'supplierProducts.productUnit', 'supplierGallery', 'companyAddress'])->where('profile_username', $username)->first();
                foreach ($supplier->supplierProducts->toArray() as $key => $supplierProduct){
                    if($supplierProduct['product']['status'] == 0){
                        unset($supplier->supplierProducts[$key]);
                    }
                    if($supplierProduct['is_deleted'] == 1){
                        unset($supplier->supplierProducts[$key]);
                    }
                }
                if (!empty($supplier)) {
                    return View::make('home.supplier.professionalProfile')->with(compact(['supplier', 'supplierId']));
                } else {
                    Log::critical('Code - 403 | ErrorCode:B030 Supplier Profile Front');
                    abort(404);
                }
            } else {
                Log::critical('Code - 403 | ErrorCode:B030 Supplier Profile Front');
                abort(404);
            }
        } catch (\Exception $exception) {
            Log::critical('Code - 503 | ErrorCode:B030 Supplier Profile Front');
            abort(404);
        }
    }

    /** Check profile username validation and uniquness end */
    public function checkProfileUsernameUnique(Request $request)
    {
        $value = $request->value;
        $where = "";
        $query = Supplier::where('profile_username', $value)->where('id', '<>', $request->id)->count();
        if ($query > 0) {
            return response()->json(['success' => false, 'message' => 'The profile username has already been taken.']);
        } else {
            return response()->json(['success' => true, 'message' => '']);
        }
    }
}

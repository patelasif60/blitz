<?php

namespace App\Http\Controllers;

use App\Models\BlockedContact;
use App\Models\Category;
use App\Models\Role;
use App\Models\SubCategory;
use App\Models\SupplierProduct;
use App\Models\UserActivity;
use App\Models\UserSupplier;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\Order;
use App\Models\User;
use App\Models\UserCompanies;
use App\Models\Quote;
use App\Models\Languages;
use App\Models\Company;
use App\Models\ContactUs;
use App\Models\SystemActivity;
use Illuminate\Support\Facades\DB;
use Session;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create contact|edit contact|delete contact|publish contact|unpublish contact', ['only' => ['contactList']]);
        $this->middleware('permission:create contact', ['only' => ['create']]);
        $this->middleware('permission:edit contact', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete contact', ['only' => ['destroy']]);
    }

    function index()
    {
        $user = Auth::user();

        if($user->language_id){
            $langs = Languages::find($user->language_id);
            //print_r($langs);
            //exit();
        }
        if (session()->has('locale')) {

        }else{
            if($langs){
                //App::setLocale(strtolower($langs->name));
            }
        }
        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $products = count(SupplierProduct::where('supplier_id', $supplier_id)->where('is_deleted', 0)->get());
            $suppliers = 0;
            $orders = Order::where('supplier_id', $supplier_id)->where('orders.is_deleted', 0)->count();
            $rfqs = count(DB::table('rfqs')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('sub_categories', 'rfq_products.sub_category_id', '=', 'sub_categories.id')
                ->join('products', 'rfq_products.product_id', '=', 'products.id')
                ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                ->join('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                ->where('suppliers.status', 1)
                ->where('suppliers.is_deleted', 0)
                ->where('suppliers.id', $supplier_id)
                ->where('rfqs.group_id',null)
                ->where('supplier_products.is_deleted', 0)->groupBy('rfqs.id')->get());
            $users = 0;
            $quotes = count(Quote::join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')->where('quote_items.supplier_id', $supplier_id)->where('is_deleted', 0)->get());
        }
        else if(Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $assignedSubCategory = SubCategory::whereIn('category_id', $assignedCategory)->pluck('id')->toArray();

            $products = count(Product::all()->whereIn('subcategory_id', $assignedSubCategory)->where('is_deleted', 0));

            $suppliers = DB::table('suppliers')
                            ->leftJoin('supplier_products', 'suppliers.id', '=', 'supplier_products.supplier_id')
                            ->leftJoin('products', 'supplier_products.id', '=', 'supplier_products.supplier_id')
                            ->whereIn('products.subcategory_id', $assignedSubCategory)
                            ->where('suppliers.is_deleted', 0)
                            ->distinct()
                            ->count();

            $users = DB::table('users')
                        ->leftJoin('company_consumptions', 'users.id', '=', 'company_consumptions.user_id')
                        ->whereIn('company_consumptions.product_cat_id', $assignedCategory)
                        ->distinct()
                        ->count();

            $orders = DB::table('orders')
                        ->leftjoin('order_items', 'orders.id', '=', 'order_items.order_id')
                        ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                        ->whereIn('products.subcategory_id', $assignedSubCategory)
                        ->where('orders.is_deleted', 0)
                        ->distinct()
                        ->count();

            $rfqs = DB::table('rfqs')
                        ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
                        ->whereIn('rfq_products.category_id', $assignedCategory)
                        ->where('rfqs.is_deleted', 0)
                        ->count();

            $quotes = DB::table('quotes')
                        ->leftjoin('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
                        ->leftJoin('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
                        ->leftJoin('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                        ->whereIn('rfq_products.category_id', $assignedCategory)
                        ->where('quotes.is_deleted', 0)
                        ->count();

        }
        else if(Auth::user()->hasRole('jne')) {
            $orders = DB::table('orders')
                ->leftjoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                ->leftJoin('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->whereIn('quotes.id', (new QuoteController())->getJneActivityQuote())
                ->where('orders.is_deleted', 0)
                ->distinct()
                ->count();

            $quotes = DB::table('quotes')
                ->leftjoin('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
                ->leftJoin('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
                ->leftJoin('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->where('quotes.status_id',Quote::PARTIAL_QUOTE)
                ->orwhereIn('quotes.id', (new QuoteController())->getJneActivityQuote())
                ->where('quotes.is_deleted', 0)
                ->distinct('quotes.id')
                ->count();

        }
        else {
            $products = count(Product::all()->where('is_deleted', 0));
            $suppliers = count(Supplier::all()->where('status','=',1)->where('is_deleted', 0));
            $orders = count(Order::all()->where('is_deleted', 0));
            $rfqs = count(Rfq::all()->where('is_deleted', 0));
            $users = count(User::all()->where('is_deleted', 0));
            $quotes = count(Quote::all()->where('is_deleted', 0));

        }

        if (Auth::user()->hasRole('finance')) {
            $buyers = User::where('role_id', Role::BUYER)->where('is_delete', 0)->where('users.approval_invite',0)->count();
        }

        /**begin: system log**/
        User::bootSystemView(new User(), "Dashboard", SystemActivity::VIEW);
        /**end:  system log**/

        return view('admin/dashboard', ['products' => $products ?? 0, 'suppliers' => $suppliers ?? 0, 'orders' => $orders, 'rfqs' => $rfqs ?? 0, 'users' => $users ?? 0,'quotes' => $quotes ?? 0, 'buyers' => $buyers ?? 0]);
    }

    public function changePasswordView(){
        if (auth()->check()){
            return view('admin/supplierPassword');
        } else {
            return view('admin/login');
        }
    }

    public function updatePassword(Request $request){
        $user = User::find(auth()->user()->id);
        if (!password_verify($request->oldpassword, $user->password)){
            return response()->json(array('success' => false, 'message' => __('admin.change_password_faild_message'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4' ));
        }
        $user->password = Hash::make($request->password);
        $user->first_login = 1;
        $user->save();

        /**begin: system log**/
        $user->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true, 'message' => __('admin.change_password_success_message'), 'heading' => 'Success', 'icon' => 'success', 'loaderBg' => '#f96868', 'url' => 'dashboard' ));
    }

    /**
     * Ronak Bhabhor 31-03-2022
     * resetPasswordView: profile details view and change password
     */
	public function resetPasswordView(){
		$department = Department::select('id','name')->get()->toArray();
		$designation = Designation::select('id','name')->get()->toArray();
        $profile_details = $this->getProfileDetails();
		// dd($profile_details);
        if (auth()->check()){
            /**begin: system log**/
            User::bootSystemView(new User(), 'Profile', SystemActivity::RECORDVIEW, Auth::user()->id);
            /**end: system log**/
            return view('admin/resetPassword', compact('profile_details','department','designation'));
        } else {
            return view('admin/login');
        }
    }

    /**
     * Ronak Bhabhor 29-03-2022
     * adminProfileUpdate: update profile details of current logged in user
     */

    public function adminProfileUpdate(Request $request)
    {
		if(!auth()->check()){
            return json_encode(['status' => false, 'message' => "Please login first"]);
        }
		$user_update_array = [
            'salutation' => $request->salutation,
			'firstname' => $request->firstname, 'lastname' => $request->lastname,
            'designation' => $request->designation,
			'department' => $request->department
        ];
		$auth_user = auth()->user();

		if ($request->cropImageSrc && $request->user_pic) {
            $image = $request->cropImageSrc;
            $destinationPath = config('settings.profile_images_folder') . '/';
            if (empty(strpos($image,$destinationPath))) {
                if(!empty($user->profile_pic) && file_exists(storage_path($user->profile_pic))) {
                    unlink(storage_path($user->profile_pic));
                }
                list($type, $image) = explode(';', $image);
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);
                $image_name = Str::random(10) . '_' . time() . 'pic_' . '.png';

                $filename = $image_name;
                Storage::disk('public')->put($destinationPath . $filename, $image, 'public');

                $user_update_array['profile_pic'] = $destinationPath . $filename;
            }
        }

		/*
		if($request->file('user_pic')) {
            $image = $request->file('user_pic');

            if($request->file('user_pic')){
				Storage::delete('/public/' . auth()->user()->profile_pic);
				$profilePicName = Str::random(10) . '_' . time() . 'logo_' . $request->file('user_pic')->getClientOriginalName();
				$profilePicName = str_replace(" ","",$profilePicName);
				$profilePicPath = $request->file('user_pic')->storeAs('/', $profilePicName, 'public');
				$user_update_array['profile_pic'] = $profilePicPath;
			}
        }
		*/

		$user = User::find(auth()->user()->id)->update($user_update_array);
		if(isset(auth()->user()->supplierId()->supplier_id) && auth()->user()->supplierId()->supplier_id){
            $update = Supplier::find(auth()->user()->supplierId()->supplier_id)
                ->update([
                    'salutation' => $request->salutation, 'contact_person_name' => $request->firstname, 'contact_person_last_name' => $request->lastname, 'alternate_email' => $request->alternate_email
                ]);
			if(!$update){
				return response()->json(array('success' => false, 'message' => __('profile.something_went_wrong'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'url' => 'admin/reset-password'));
			}
        }
		if($user){
			$profile_data = $this->getProfileDetails();
			return response()->json(['success' => true, 'message' => __('profile.profile_update_success'), 'heading' => 'Success', 'icon' => 'success', 'loaderBg' => '#f96868', 'data' => $profile_data]);
		}else{
			return response()->json(array('success' => false, 'message' => __('profile.something_went_wrong'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'url' => 'admin/reset-password'));
		}
    }

	/**
     * Ronak Bhabhor 31-03-2022
     * getProfileDetails: profile details view and change password
     */
	public function getProfileDetails()
	{
		$profile_details = [];
        if(auth()->check()){
			$auth_user = User::find(auth()->user()->id);
            $profile_details = [
				'user_id' => $auth_user->id,
                'is_active' => $auth_user->is_active,
                'salutation' => $auth_user->salutation, 'firstname' => $auth_user->firstname,  'lastname' => $auth_user->lastname,
                'email' => $auth_user->email, 'designation_name' => null,
                'designation' => $auth_user->designation, 'department' => $auth_user->department,
                'mobile' => $auth_user->mobile, 'phone_code' => str_replace('+','',$auth_user->phone_code),
				'country_code' => $auth_user->phone_code ? strtolower(getRecordsByCondition('countries',['phone_code'=>$auth_user->phone_code],'iso2',1)):'id',
				'profile_pic' =>  null
            ];


			if($profile_details['designation']){
				$des_record = Designation::where('id', $profile_details['designation'])->get()->first();
				if($des_record){
					$profile_details['designation_name'] = $des_record->name;
				}
			}else{
				if(auth()->user()->role_id == 3){
					$profile_details['designation_name'] = 'Supplier';
				}else{
					$profile_details['designation_name'] = 'Manager';
				}
			}

			if($auth_user->profile_pic){
				$profile_details['profile_pic'] = asset('storage').'/'.$auth_user->profile_pic;
			}
			// dd($profile_details);
            if(isset($auth_user->supplierId()->supplier_id) && $auth_user->supplierId()->supplier_id){
                $supplier_details = Supplier::find($auth_user->supplierId()->supplier_id);
                $profile_details['alternate_email'] = $supplier_details['alternate_email'] ?? '';
            }
        }
		return $profile_details;
	}
	function contactList(){
        $contact = DB::table('contact_us')->orderBy('id', 'desc')->get();
        $blockedContact = BlockedContact::orderBy('id', 'desc')->pluck('contact_email')->toArray();
        /**begin: system log**/
        ContactUs::bootSystemView(new ContactUs());
        /**end:  system log**/
        return view('admin/contact', ['contactData' => $contact,'blockedContact'=>$blockedContact]);
    }
    function blockContact(Request $request){
                $blocked_contact = new BlockedContact();
                $blocked_contact->contact_email = $request->id;
                $blockedContactData = $blocked_contact->save();
            return response()->json(array('success' => true));
    }
    function unblockContact(Request $request){
	    if (isset($request->id)){
	        BlockedContact::where('contact_email',$request->id)->delete();

            return response()->json(array('success' => true));
        }
    }
    //change mobile page
    public function changemobile(){
        $userData = User::find(Auth::user()->id);
        $phoneCode = $userData->phone_code ? str_replace('+','',$userData->phone_code):62;
        $country = strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)); 
        return view('mobileotp/mobileVarificationSupplier',compact('userData','country','phoneCode'));
    }
}

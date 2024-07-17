<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserAddresse extends Model
{
    use HasFactory, SystemActivities;

    const OtherCity     = -1;
    const OtherState    = -1;

    protected $tagname = "User Address";

    protected $fillable = [
        'user_id',
        'user_type',
        'default_address',
        'address_name',
        'address_line_1',
        'address_line_2',
        'city',
        'sub_district',
        'district',
        'state',
        'pincode',
        'city_id',
        'state_id',
        'country_one_id',
        'company_id',
        'is_user_primary',
        'is_deleted'
    ];


    public static function createOrUpdateUserAddress($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {

            $result->fill($data)->save();
            self::where(['id' => $data['id']])->update([
                'state'     =>  $data['stateEdit'],
                'state_id'  =>  $data['stateEdit_id'],
                'city'      =>  $data['cityEdit'],
                'city_id'   =>  $data['cityEdit_id']
            ]);
            return $result;
        }
    }

    /**
     * getCompanyBuyerAddress: Address query changed based on roles and permission changes
     *  */
    public static function getCompanyBuyerAddress()
    {
        $authUser = Auth::user();
        $userAddress = self::all()
        ->where('is_deleted', 0);

        /***************************begin: Get address by set permissions**************************************/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer address')){
            $userAddress = $userAddress->where('company_id', $authUser->default_company);
        }else {
            $userAddress = $userAddress->where('user_id', $authUser->id)->where('company_id', $authUser->default_company);
        }
        /***************************end: Get address by set permissions**************************************/
        $userAddress = $userAddress->sortByDesc('id');
        return $userAddress;
    }

    /**
     * Get the city associated with the user address.
     */
    public function getCity()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->first();
    }

    /**
     * Get the state associated with the user address.
     */
    public function getState()
    {
        return $this->belongsTo(State::class, 'state_id', 'id')->first();

    }

    /**
     * Get the country associated with the user address.
     */
    public function getCountryOne()
    {
        return $this->belongsTo(CountryOne::class, 'country_one_id', 'id')->first();
    }

    /**
     * Get user details.
     */
    public function getUserDetails()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    /**
     * Check user set address to primary
     */
    public function  isPrimaryAddress()
    {
        $authUser = Auth::user();
        return self::whereJsonContains('is_user_primary', [$authUser->id])->where('company_id',$authUser->default_company)->where('is_deleted',0)->first();
    }

    /**
     * Update and remove user id for user primary address
     * @param $userIdArr - Unset user id from primary addrees and update user id
     * @param null $id - for update address with address id
     */
    public function  updatePrimaryAddress($userIdArr,$id=null,$userId=null)
    {
        $authUser = Auth::user();
        if(isset($userId) && !empty($userId)){
            return self::where(['user_id' => $userId])->where('company_id',$authUser->default_company)->where('id',$id)->update(['is_user_primary'=>$userIdArr]);
        }elseif(isset($id) && !empty($id)) {
                return self::where(['user_id' => $authUser->id])->where('company_id',$authUser->default_company)->where('id',$id)->update(['is_user_primary'=>$userIdArr]);
        }
    }

    /**
     * Check other user has already set primary to this address
     * @param $id - address id
     */
    public function checkPrimaryUserAddress($id)
    {
        $authUser = Auth::user();
        return self::where('company_id',$authUser->default_company)->where('id',$id)->where('is_deleted',0)->first();

    }

    /**
     * Get login user Address for that company
     *
     */
    public function getUserwiseAddress() {
        $authUser = Auth::user();
        return self::where(['user_id' => $authUser->id])->where('company_id',$authUser->default_company)->where('is_deleted',0)->get()->sortByDesc('id');
    }
    /**
     * Get other user's address for that company expect login user
     */
    public function getOtherUserAddress() {
        $authUser = Auth::user();
        /***************************begin: Get address by set permissions**************************************/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer address')){
            $userAddress = self::where('user_id','!=', $authUser->id)->where('company_id',$authUser->default_company)->where('is_deleted',0)->get()->sortByDesc('id');
        }else {
            $userAddress = [];
        }
        /***************************end: Get address by set permissions**************************************/
        return $userAddress;
    }
    /**
     * Get Address details by id
     */
    public function getAddressById($id){
        return self::where('id','=', $id)->where('is_deleted',0)->first();
    }
    /**
     * Get company wise address list
     */
    public function companyWiseAddress()
    {
        return self::where('company_id', Auth::user()->default_company)->where('is_deleted', 0)->get()->sortByDesc('id');
    }

    /**
     * Is model belongs to other resources
     *
     * @param $model_id
     * @param null $user_id
     * @param null $company_id
     * @return mixed
     */
    public function isBelongsTo ($model_id, $user_id = null, $company_id = null)
    {
        $query = self::where('id',$model_id);

        !empty($user_id) ? $query->where('user_id', $user_id) : $query;

        !empty($company_id) ? $query->where('company_id', $company_id) : $query;

        return $query;
    }

}

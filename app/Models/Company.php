<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Company extends Model
{
    use HasFactory,HybridRelations,SystemActivities;
    protected $connection = 'mysql';
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'interested_product',
        'logo',
        'background_logo',
        'registrantion_NIB',
        'nib_file',
        'npwp',
        'npwp_file',
        'termsconditions_file',
        'web_site',
        'company_email',
        'c_phone_code',
        'company_phone',
        'alternative_email',
        'a_phone_code',
        'alternative_phone',
        'address',
        'approval_process',
        'owner_user',
        'background_colorpicker',
        'updated_by',
        'created_by',
        'is_deleted'
    ];

    /**
     * Equolent relationship b/n Company and CustomRole
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customRole()
    {
        return $this->hasOne(CustomRoles::class,'company_id','id');
    }

    /**
     * Equolent relationship b/n Company and CustomRoles
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customRoles()
    {
        return $this->hasMany(CustomRoles::class,'company_id','id');
    }

    /**
     *
     * Get user assigned company
     *
     * @param $assignedCompany
     * @return string
     */
    public function getUserAssignCompanyAttribute($assignedCompany)
    {
        $companies = Company::whereIn('id', $assignedCompany);
        return $companies->get()->isEmpty() ? '' : $companies;
    }

     /**
     * company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCompanies()
    {
        return $this->hasMany(UserCompanies::class);
    }

    /**
     * Equolent relationship b/n Company and CustomRole
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Set company owner and return company id for create default admin role.

    public static function setCompanyOwner($userId)
    {
        $reqUser = User::where('id', $userId)->first();
        $userAssignedCompanies = $reqUser->assigned_companies;

        $companyIds = json_decode($userAssignedCompanies);

        if(sizeof($companyIds) > 0){
            foreach ($companyIds as $companyId) {
                $ownerId = Company::where('id', $companyId)->first()->owner_user;
                if($ownerId == $userId){
                    return $companyId;
                }
            }

            $newCompanyIds = [];
            $newCompany = new Company;
            $newCompany->name = $reqUser['firstname'];
            $newCompany->owner_user = $userId;
            $newCompany->created_by = $userId;

            foreach ($companyIds as $company) {
                array_push($newCompanyIds, $company);
            }

            if($newCompany->save()){
                $assignedCompaniesArr = !empty($userAssignedCompanies) ? json_decode($userAssignedCompanies) : [];
                if (!in_array($newCompany->id, $assignedCompaniesArr)) {
                    array_push($assignedCompaniesArr, $company);
                }
                $response = User::where('id', $userId)->update(['assigned_companies' => json_encode($assignedCompaniesArr)]);
                return $newCompany->id;
            }

        }
    }
    public function companyOtherInformation()
    {
        return $this->hasOne(CompanyOtherInformation::class);
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','owner_user');
    }
    public function companyConsumption()
    {
        return $this->hasMany(CompanyConsumption::class,'user_id','owner_user');
    }


}

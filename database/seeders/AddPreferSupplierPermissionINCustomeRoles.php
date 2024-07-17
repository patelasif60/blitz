<?php

namespace Database\Seeders;

use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\ModelHasCustomPermission;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AddPreferSupplierPermissionINCustomeRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $customRoles = CustomRoles::where('system_default_role','=',0)->OrWhereNull('system_default_role')->get(); // get only custom role
            $permissions = [248, 311, 312, 313, 314, 315, 326]; // Prefer supplier permission id

            $customRoles->each(function($role) use ($permissions) {

                foreach (Arr::flatten(json_decode($role->permissions, true)) as $oldpermission) {
                    array_push($permissions,$oldpermission); // push prefer supplier permission id array to old permission
                }
                $customRole = CustomRoles::where('id', $role->id)->update([
                    'permissions'       =>  json_encode($permissions), // update new permission to role
                ]);

                if ($customRole) {
                    $customPermission = CustomPermission::where('model_type', CustomRoles::class)->where('value', $role->id)->first();
                    if (!empty($customPermission->modelHasCustomPermission())) {

                        ModelHasCustomPermission::where('custom_permission_id', $customPermission->id)->update([
                            'custom_permissions' => json_encode($permissions) // update new permission to modal has custom permission
                        ]);
                    }
                }
            });
            dd("Update Prefer supplier permission in tables ");

        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}

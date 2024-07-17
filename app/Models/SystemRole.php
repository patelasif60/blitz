<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Guard;

class SystemRole extends Model
{
    use HasFactory, SystemActivities;

    /** @var int Main Roles  */
    const BACKOFFICE        =   1;
    const SUPPLIEROFFICE    =   2;
    const FRONTOFFICE       =   3;

    protected $fillable = [
        'name',
        'guard_name'
    ];

    /**
     * Get custom permission by name.
     */
    public static function findByName(string $name, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $customPermission = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        return $customPermission;
    }

    /**
     * Get custom permission by params.
     */
    protected static function findByParam(array $params = [])
    {
        $query = SystemRole::where('name', $params['name'])->where('guard_name', $params['guard_name'])->first();

        return $query;
    }
}

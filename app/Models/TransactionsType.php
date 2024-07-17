<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsType extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = [
        'name', 'description', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];
}

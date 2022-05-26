<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'address', 'created_by', 'updated_by'
    ];
    public function scopeFilter($query, $filter)
    {

        if (isset($filter['search']) && $search = $filter['search']) {
            $query->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('email', 'ILIKE', "%{$search}%")
                ->orWhere('address', 'ILIKE', "%{$search}%");
        }
    }
}

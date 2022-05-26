<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'created_by', 'updated_by'
    ];
    public function scopeFilter($query, $filter)
    {

        if (isset($filter['search']) && $search = $filter['search']) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }
    }
}

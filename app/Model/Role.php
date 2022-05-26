<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'created_by', 'updated_by', 'deleted_by'];
    public function is_admin()
    {
        return $this->name === 'Admin' || false;
    }
    public function scopeFilter($query, $filter)
    {

        if (isset($filter['search']) && $search = $filter['search']) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }
    }
}

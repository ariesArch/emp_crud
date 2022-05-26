<?php

namespace App\Model;

use App\Model\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasApiTokens, SoftDeletes;
    protected $guard = 'employee-web';

    public function findForPassport($staff_id)
    {
        return $this->where('username', $staff_id)->first();
    }

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'department_id', 'company_id', 'address', 'staff_id', 'role_id', 'created_by', 'updated_by'
    ];
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function setStaffIdAttribute($value)
    {
        $this->attributes['staff_id'] = str_pad($value, 4, '0', STR_PAD_LEFT);
    }
    public function scopeFilter($query, $filter)
    {
        if (isset($filter['search']) && $search = $filter['search']) {
            // Ecact Keyword
            // $names = explode(" ", $search);
            // $query->where(function ($query) use ($names) {
            //     $query->whereIn('first_name', $names)
            //         ->orWhereIn('last_name', $names);
            // });
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'ILIKE', "%{$search}%");
                $query->orWhere(function ($query) use ($search) {
                    $query->where('last_name', 'ILIKE', "%{$search}%");
                });
            });
        }
        if (isset($filter['company_id']) && $company_id = $filter['company_id']) {
            $query->where('company_id', $company_id);
        }
        if (isset($filter['department_id']) && $department_id = $filter['department_id']) {
            $query->where('department_id', $department_id);
        }
    }
    public function company()
    {
        return $this->belongsTo(Company::class)->withTrashed();;
    }
    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();;
    }
    public function role()
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }

    public function hasRole($role)
    {
        if ($this->role->where('name', $role)->first()) {
            return true;
        }

        return false;
    }
}

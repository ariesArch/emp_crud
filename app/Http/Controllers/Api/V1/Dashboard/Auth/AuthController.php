<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UpdatePasswordRequest;
use App\Http\Resources\Api\V1\Role\RoleResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);
    //     \Log::info(print_r(auth()->guard(), true));
    //     if (!auth()->attempt($credentials)) {
    //         return response()->json(['status' => 2, 'message' => 'email or password wrong!']);
    //     }
    //     $accessToken = auth()->user()->createToken('authToken')->accessToken;
    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Success',
    //         'accessToken' => $accessToken,
    //         'user' => auth()->user()
    //     ]);
    // }
    public function logout()
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'status' => 1,
            'message' => 'Successful logout',
        ], 200);
    }
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'staff_id' => 'required',
            'password' => 'required'
        ]);
        if (!Auth::guard('employee-web')->attempt($loginData)) {
            return response(['status' => 2, 'message' => 'Invalid Credentials']);
        }
        $accessToken = Auth::guard('employee-web')->user()->createToken('authToken')->accessToken;
        $user = Auth::guard('employee-web')->user();
        return response()->json([
            'status' => 1,
            'user' =>  [
                'id' => $user->id,
                'name' => $user->full_name,
                'phone' => $user->phone,
                'role' => RoleResource::make($user->role),
                'is_admin' => $user->role->is_admin(),
                'comapany' => CompanyResource::make($user->company),
                'department' => DepartmentResource::make($user->department),
            ],
            'access_token' => $accessToken
        ]);
    }
    public function update_password(UpdatePasswordRequest $request)
    {
        $employee = Auth::guard('employee-api')->user();
        $employee->password = isset($request->new_password) ? Hash::make($request->new_password) : $employee->password;
        if ($employee->isDirty()) {
            $employee->updated_by = auth()->user()->id;
            $employee->save();
        }
        return response()->json([
            'status' => 1,
            'message' => 'Successfully updated!'
        ], 200);
    }
}

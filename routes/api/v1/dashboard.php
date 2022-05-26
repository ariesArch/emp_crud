<?php

use Illuminate\Support\Facades\Route;


Route::post('auth/login', 'Auth\AuthController@login');
Route::group(['middleware' => ['auth:employee-api']], function () {
    // Route::resource('/companies', 'CompanyController');
    // Route::resource('/departments', 'DepartmentController');
    // Route::resource('/employees', 'EmployeeController');
    Route::post('auth/update_password', 'Auth\AuthController@update_password');
    Route::get('auth/logout', 'Auth\AuthController@logout');
    // MasterRecords
    Route::get('/master_records', ['uses' => 'MasterRecordController@index', 'middleware' => 'roles', 'roles' => ['Admin', 'Employee']]);
    // Company
    Route::get('/companies', ['uses' => 'CompanyController@index', 'middleware' => 'roles', 'roles' => ['Admin', 'Employee']]);
    Route::post('/companies', ['uses' => 'CompanyController@store', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::put('/companies/{company}', ['uses' => 'CompanyController@update', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::delete('/companies/{company}', ['uses' => 'CompanyController@destroy', 'middleware' => 'roles', 'roles' => ['Admin']]);
    // Department
    Route::get('/departments', ['uses' => 'DepartmentController@index', 'middleware' => 'roles', 'roles' => ['Admin', 'Employee']]);
    Route::post('/departments', ['uses' => 'DepartmentController@store', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::put('/departments/{department}', ['uses' => 'DepartmentController@update', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::delete('/departments/{department}', ['uses' => 'DepartmentController@destroy', 'middleware' => 'roles', 'roles' => ['Admin']]);
    // Employee
    Route::get('/employees', ['uses' => 'EmployeeController@index', 'middleware' => 'roles', 'roles' => ['Admin', 'Employee']]);
    Route::post('/employees', ['uses' => 'EmployeeController@store', 'middleware' => 'roles', 'roles' => ['Admin', 'Employee']]);
    Route::put('/employees/{employee}', ['uses' => 'EmployeeController@update', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::delete('/employees/{employee}', ['uses' => 'EmployeeController@destroy', 'middleware' => 'roles', 'roles' => ['Admin']]);
});

<?php

namespace App\Http\Resources\Api\V1\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Api\V1\Role\RoleResource;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects = RoleResource::class;
    public function toArray($request)
    {
        return parent::toArray($request);
    }
    public function with($request)
    {
        return [
            'status' => 1,
        ];
    }
}

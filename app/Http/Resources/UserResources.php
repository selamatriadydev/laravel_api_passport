<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'data' => [
                'categories' => $this->collection->map(function($data) {
                    return [
                        'id'         => $data->id,
                        'name'       => $data->category_name,
                        'slug'       => $data->category_slug,
                        'created_at'    => Carbon::parse($data->created_at)->toDateTimeString()
                    ];
                })
            ]
        ];
    }
    public function with($request)
    {
        return [
            'isSuccess' => true,
            'message' => ''
        ];
    }
}

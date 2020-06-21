<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'name'=> $this->name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'birth_date'=>$this->birth_date,
            'country' =>$this->country,
            'image'=>$this->image,
            'image_path'=>$this->image_path,
            'is_admin'=>$this->is_admin,
            'created_at'=>$this->created_at,
        ];



        return $response;
    }
}

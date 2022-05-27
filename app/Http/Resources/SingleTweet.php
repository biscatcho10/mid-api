<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleTweet extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->author->id,
            'user_name' => $this->author->name,
            'content' => $this->content,
            'likes' => $this->users()->count(),
            'liked_users' => $this->users->pluck('name')->toArray(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'comment'    => $this->comment,
            'status'     => $this->status,
            'user'       => UserResource::make($this->whenLoaded('user')),
            'post'       => PostResource::make($this->whenLoaded('post')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

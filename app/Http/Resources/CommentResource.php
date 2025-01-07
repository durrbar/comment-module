<?php

namespace Modules\Comment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Resources\UserResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            "parentId"          => $this->whenHas('parent_id'),
            "userId"            => $this->whenHas('user_id'),
            "commentableType"   => $this->commentable_type,
            "commentableId"     => $this->commentable_id,
            "content"           => $this->content,
            "createdAt"         => $this->created_at,
            "updatedAt"         => $this->updated_at,
            "deletedAt"         => $this->deleted_at,
            "user"              => new UserResource($this->user),
            "comments"           => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}

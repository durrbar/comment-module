<?php

declare(strict_types=1);

namespace Modules\Comment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Modules\User\Resources\UserJsonApiResource;
use Override;

class CommentJsonApiResource extends JsonApiResource
{
    /**
     * Transform the resource into an array.
     */
    public function toAttributes(Request $request): array
    {
        return [
            'parentId' => $this->whenHas('parent_id'),
            'userId' => $this->whenHas('user_id'),
            'commentableType' => $this->commentable_type,
            'commentableId' => $this->commentable_id,
            'content' => $this->content,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
        ];
    }
    
    public function toRelationships(Request $request)
    {
        return [
            'user' => UserJsonApiResource::class,
            'comments' => self::class,
        ];
    }

    public function toType(Request $request)
    {
        return 'comments';
    }
}

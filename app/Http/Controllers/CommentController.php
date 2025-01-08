<?php

namespace Modules\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Http\Requests\CommentRequest;
use Modules\Comment\Models\Comment;
use Modules\Comment\Http\Resources\CommentCollection;
use Modules\Comment\Http\Resources\CommentResource;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index($modelType, $modelId)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        // Build the base query for comments
        $query = $model->comments()->with(['user', 'comments.user'])->whereNull('parent_id')->orderBy('created_at', 'desc');

        // Paginate the comments for performance
        $comments = $query->paginate(2);

        // Use CommentCollection for paginated response
        return response()->json(['comments' => new CommentCollection($comments)], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, $modelType, $modelId): JsonResponse
    {
        $validatedData = $request->validated();

        $model = $this->getModelInstance($modelType, $modelId);

        $comment = $model->comments()->create([
            'content' => $validatedData['content'],
            'user_id' => Auth::id(),
            'parent_id' => $validatedData['parent_id'] ?? null,
        ]);

        // Return the newly created comment as a resource
        return response()->json(['comment' => new CommentResource($comment->load('user', 'comments.user'))], Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     */
    public function show($modelType, $modelId, Comment $comment): JsonResponse
    {
        // Authorize the action using policies
        $this->authorize('view', $comment);

        // Return a single comment
        return response()->json(['comment' => new CommentResource($comment)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, $modelType, $modelId, Comment $comment): JsonResponse
    {
        // Authorize the action using policies
        $this->authorize('update', $comment);

        $validatedData = $request->validated();

        $comment->update([
            'content' => $validatedData['content'],
        ]);

        // Return updated comment as a resource
        return response()->json(['comment' => new CommentResource($comment->load('user', 'comments'))], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($modelType, $modelId, Comment $comment): JsonResponse
    {
        // Authorize the action using policies
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Get model instance based on type.
     */
    private function getModelInstance($modelType, $modelId)
    {
        // This resolves the model class based on the polymorphic type
        $modelClass = Relation::getMorphedModel($modelType);

        if (!$modelClass) {
            abort(Response::HTTP_NOT_FOUND, "Invalid model type");
        }

        return $modelClass::findOrFail($modelId);
    }
}

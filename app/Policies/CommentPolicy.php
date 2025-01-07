<?php

namespace Modules\Comment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Comment\Models\Comment;
use Modules\User\Models\User;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

        /**
     * Determine whether the user can view any models.
     *
     * @param User $user The authenticated user.
     * @return bool True if viewing any comments is allowed, otherwise false.
     */
    public function viewAny(User $user): bool
    {
        // Allow all authenticated users to view any comments.
        return true;
    }

    /**
     * Determine whether the user can view a specific model.
     *
     * @param User $user The authenticated user.
     * @param Comment $comment The comment being accessed.
     * @return bool True if viewing the comment is allowed, otherwise false.
     */
    public function view(User $user, Comment $comment): bool
    {
        // Allow all authenticated users to view a specific comment.
        return true;
    }

    /**
     * Determine whether the user can create a comment.
     *
     * @param User $user The authenticated user.
     * @return bool True if the user can create comments, otherwise false.
     */
    public function create(User $user): bool
    {
        // User must be logged in to create a comment.
        return $user->exists();
    }

    /**
     * Determine whether the user can update a specific comment.
     *
     * @param User $user The authenticated user.
     * @param Comment $comment The comment being updated.
     * @return bool True if the user can update the comment, otherwise false.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Allow super-admins or the owner of the comment to update it.
        return $user->hasRole('super-admin') || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete a specific comment.
     *
     * @param User $user The authenticated user.
     * @param Comment $comment The comment being deleted.
     * @return bool True if the user can delete the comment, otherwise false.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Allow super-admins or the owner of the comment to delete it.
        return $user->hasRole('super-admin') || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore a specific comment.
     *
     * @param User $user The authenticated user.
     * @param Comment $comment The comment being restored.
     * @return bool True if the user can restore the comment, otherwise false.
     */
    public function restore(User $user, Comment $comment): bool
    {
        // Only super-admins can restore comments.
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete a specific comment.
     *
     * @param User $user The authenticated user.
     * @param Comment $comment The comment being permanently deleted.
     * @return bool True if the user can permanently delete the comment, otherwise false.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        // Only super-admins can permanently delete comments.
        return $user->hasRole('super-admin');
    }
}

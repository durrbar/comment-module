<?php

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

// use Modules\Comment\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * The provider name.
     *
     * @var string
     */
    protected $provider;

    /**
     * The table associated with the model.
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['content', 'user_id', 'parent_id'];

    // protected static function newFactory(): CommentFactory
    // {
    //     // return CommentFactory::new();
    // }

    /**
     * Get the parent commentable model (post or video).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return the user relationship.
     */
    public function user(): BelongsTo
    {
        $providerName = Config::get("auth.guards." . Auth::getDefaultDriver() . ".provider");

        return $this->belongsTo(Config::get("auth.providers.{$providerName}.model"), 'user_id');
    }

    /**
     * Return the replies relationship.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('comments');
    }
}

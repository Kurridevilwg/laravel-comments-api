<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    protected $fillable = ['user_id', 'content', 'commentable_type', 'commentable_id', 'parent_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Для полиморфного
    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('user:id,name');
    }
}

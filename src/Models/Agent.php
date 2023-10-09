<?php

namespace Flarum\Ai\Models;

use Flarum\Ai\Models\Concerns\Authorization;
use Flarum\Database\AbstractModel;
use Flarum\Group\Group;
use Flarum\Tags\Tag;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agent extends AbstractModel
{
    protected $table = 'ai_agents';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'ai_agent_tags'
        )->withPivot('authorization');
    }

    public function allowedTags(): BelongsToMany
    {
        return $this->tags()
            ->wherePivot(
                'authorization',
                Authorization::allow
            );
    }

    public function ignoredTags(): BelongsToMany
    {
        return $this->tags()
            ->wherePivot(
                'authorization',
                Authorization::ignore
            );
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'ai_agent_groups'
        )->withPivot('authorization');
    }

    public function allowedGroups(): BelongsToMany
    {
        return $this->groups()
            ->wherePivot(
                'authorization',
                Authorization::allow
            );
    }

    public function ignoredGroups(): BelongsToMany
    {
        return $this->groups()
            ->wherePivot(
                'authorization',
                Authorization::ignore
            );
    }
}

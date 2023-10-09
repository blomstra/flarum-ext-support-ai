<?php

namespace Flarum\Ai\Relations;

use Flarum\Ai\Models\Agent;
use Flarum\User\User;

class UserAiAgentRelation
{
    public function __invoke(User $user)
    {
        return $user->belongsTo(Agent::class);
    }
}

<?php

namespace Flarum\Ai\Access;

use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class UserPolicy extends AbstractPolicy
{
    public function canManageAi(User $actor, User $user)
    {
        return $actor->can('ai.manage');
    }
}

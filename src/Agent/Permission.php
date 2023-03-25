<?php

namespace Blomstra\SupportAi\Agent;

use Flarum\Discussion\Discussion;
use Flarum\User\User;

enum Permission: string
{
    case REPLY_TO_MENTIONS = 'discussion.supportAiRespondToMentions';
    case REPLY_TO_OP = 'discussion.supportAiRespondToOp';
    case REPLY_TO_REPLIES = 'discussion.supportAiRespondToReplies';

    public function authorized(User $agent, Discussion $discussion): bool
    {
        return $agent->can($this->value, $discussion);
    }
}

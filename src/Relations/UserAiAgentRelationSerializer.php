<?php

namespace Flarum\Ai\Relations;

use Flarum\Ai\Api\Serializers\AgentSerializer;
use Flarum\Ai\Models\Agent;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;

class UserAiAgentRelationSerializer
{
    public function __invoke(UserSerializer $serializer, User $user)
    {
        return $serializer->hasOne(
            model: Agent::class,
            serializer: AgentSerializer::class,
            relation: 'aiAgent'
        );
    }
}

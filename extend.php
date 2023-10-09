<?php

namespace Flarum\Ai;

use Flarum\Ai\Access\UserPolicy;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\Extend as Flarum;
use Flarum\User\User;

return [
    (new Flarum\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Flarum\Locales(__DIR__.'/resources/locale')),

    (new Flarum\Settings)->default('blomstra-ai.model', 'gpt-3.5-turbo'),

    (new Flarum\Policy)->modelPolicy(
        User::class,
        UserPolicy::class,
    ),

    (new Flarum\Model(User::class))
        ->relationship('aiAgent', Relations\UserAiAgentRelation::class),
    (new Flarum\ApiSerializer(UserSerializer::class))
        ->relationship('aiAgent', Relations\UserAiAgentRelationSerializer::class),
];

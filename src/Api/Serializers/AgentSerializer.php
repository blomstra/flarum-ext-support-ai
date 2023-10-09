<?php

namespace Flarum\Ai\Api\Serializers;

use Flarum\Ai\Models\Agent;
use Flarum\Api\Serializer\AbstractSerializer;

class AgentSerializer extends AbstractSerializer
{
    /**
     * @param Agent $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [

        ];
    }
}

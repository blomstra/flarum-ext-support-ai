<?php

use Flarum\Ai\Models\Agent;
use Flarum\Database\Migration;
use Flarum\Group\Group;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    name: 'ai_agent_groups',
    definition: function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->foreignIdFor(Agent::class, 'agent_id');
        $table->foreignIdFor(Group::class, 'group_id');

        $table->enum('authorization', ['allow' , 'ignore']);

        $table->unique(['agent_id', 'group_id']);
    }
);

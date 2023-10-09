<?php

use Flarum\Ai\Models\Agent;
use Flarum\Database\Migration;
use Flarum\Tags\Tag;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    name: 'ai_agent_tags',
    definition: function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->foreignIdFor(Agent::class, 'agent_id');
        $table->foreignIdFor(Tag::class, 'tag_id');

        $table->enum('authorization', ['allow' , 'ignore']);

        $table->unique(['agent_id', 'tag_id']);
    }
);

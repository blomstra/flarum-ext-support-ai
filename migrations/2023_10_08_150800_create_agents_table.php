<?php

use Flarum\Database\Migration;
use Flarum\User\User;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    name: 'ai_agents',
    definition: function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->foreignIdFor(User::class);

        $table->boolean('blocks_account_use')->default(false);
        $table->boolean('responds_to_mentions')->default(false);

        $table->longText('persona')->nullable();

        $table->timestamp('created_at');
    }
);

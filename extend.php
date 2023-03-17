<?php

namespace Blomstra\SupportAi;

use Flarum\Extend as Flarum;

return [
    (new Flarum\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Flarum\ServiceProvider)
        ->register(BindingsProvider::class)
        ->register(ClientProvider::class),

    (new Flarum\Event)->subscribe(Listen\GenerateFirstReplies::class),

    (new Flarum\Console)->command(Console\TrainAgentCommand::class),
];

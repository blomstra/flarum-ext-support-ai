<?php

namespace Blomstra\SupportAi;

use Flarum\Extend as Flarum;

return [
    (new Flarum\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Flarum\Locales(__DIR__.'/resources/locale')),

    (new Flarum\ServiceProvider)
        ->register(BindingsProvider::class)
        ->register(ClientProvider::class),

    (new Flarum\Event)->subscribe(Listen\ReplyToPosts::class),

    (new Flarum\Console)->command(Console\TrainAgentCommand::class),

    (new Flarum\Settings())
        ->default('blomstra-support-ai.model', 'gpt-3.5-turbo'),
];

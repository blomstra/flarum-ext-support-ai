<?php

namespace Blomstra\SupportAi;

use Blomstra\SupportAi\Agent\Reply;
use Flarum\Foundation\AbstractServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class BindingsProvider extends AbstractServiceProvider
{
    public function boot()
    {
        Reply::setEventDispatcher($this->container->make(Dispatcher::class));
    }
}

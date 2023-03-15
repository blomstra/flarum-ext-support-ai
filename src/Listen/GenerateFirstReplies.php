<?php

namespace Blomstra\SupportAi\Listen;

use Blomstra\SupportAi\Agent;
use Blomstra\SupportAi\Job\ReplyJob;
use Flarum\Post\Event\Posted;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;

class GenerateFirstReplies
{
    public function __construct(
        protected Agent $agent,
        protected Queue $queue
    ) {
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Posted::class, function (Posted $event) {
            // Do not respond to itself or if client is inactive.
            if ($this->agent->operationable() === false
                || $this->agent->is($event->post->user)
            ) {
                return;
            }

            $this->queue->push(new ReplyJob($event->post));
        });
    }
}

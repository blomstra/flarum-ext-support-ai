<?php

namespace Blomstra\SupportAi\Listen;

use Blomstra\SupportAi\Agent;
use Blomstra\SupportAi\Job\ReplyJob;
use Flarum\Post\Event\Posted;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;

class ReplyToPosts
{
    public function __construct(
        protected Agent $agent,
        protected Queue $queue
    ) {
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Posted::class, function (Posted $event) {
            if (! $this->agent->operational()
                || $this->agent->is($event->post->user)
            ) {
                return;
            }

            // Check if the agent is allowed to reply to the first post (OP).
            if ($event->post->number === 1
                && ! Agent\Permission::REPLY_TO_OP->authorized($event->post->user, $event->post->discussion)) {
                return;
            }

            // Check if the agent is allowed to reply to replies.
            if ($event->post->number > 1
                && ! Agent\Permission::REPLY_TO_REPLIES->authorized($event->post->user, $event->post->discussion)) {
                return;
            }

            // Check if the agent is allowed to reply to being mentioned.
            if ($event->post?->mentionsUsers()->wherePivot('mentions_user_id', $this->agent->user->id)->exists()
                && ! Agent\Permission::REPLY_TO_MENTIONS->authorized($event->post->user, $event->post->discussion)) {
                return;
            }

            $this->queue->push(new ReplyJob($event->post));
        });
    }
}

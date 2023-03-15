<?php

namespace Blomstra\SupportAi\Job;

use Blomstra\SupportAi\Agent;
use Flarum\Post\Post;
use Flarum\Queue\AbstractJob;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ReplyJob extends AbstractJob
{
    public function __construct(protected Post $post)
    {}

    public function handle(Agent $agent): void
    {
        $agent->repliesTo($this->post);
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        $id = $this->post->id;

        return [
            new WithoutOverlapping("blomstra-support-ai.is-replying-to-post-id.$id")
        ];
    }
}

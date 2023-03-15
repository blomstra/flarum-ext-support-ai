<?php

namespace Blomstra\SupportAi;

use Blomstra\SupportAi\Agent\Message;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\User;
use Illuminate\Support\Arr;
use OpenAI\Client;

class Agent
{
    protected bool $canMention = false;

    public function __construct(
        public readonly User $user,
        public readonly ?string $persona = null,
        protected ?Client $client = null,
    ) {}

    public function operationable(): bool
    {
        return $this->client !== null;
    }

    public function is(User $someone): bool
    {
        return $this->user->is($someone);
    }

    public function isnt(User $someone): bool
    {
        return ! $this->is($someone);
    }

    public function repliesTo(Post $post): void
    {
        $messages = Message::buildFromHistory($post);
        $messages->push(Message::buildFromPost($post));

        // Define the system role message to set the tone of voice.
        if ($this->persona) {
            $messages->prepend(Message::buildFromPersona($this));
        }

        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
        ]);

        if (empty($response->choices)) return;

        $respond = Arr::first($response->choices);

        $reply = $respond->message->content;

        if ($this->canMention) {
            $reply = sprintf(
                '@"%s"#p%u %s',
                $post->user->display_name,
                $post->id,
                $reply
            );
        }

        CommentPost::reply(
            discussionId: $post->discussion_id,
            content: $reply,
            userId: $this->user->id,
            ipAddress: '127.0.0.1'
        )->save();
    }

    public function toggleMentioning(bool $mention = true): self
    {
        $this->canMention = $mention;

        return $this;
    }
}

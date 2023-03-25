<?php

namespace Blomstra\SupportAi;

use Blomstra\SupportAi\Agent\Flag;
use Blomstra\SupportAi\Agent\Model;
use Blomstra\SupportAi\Agent\Reply;
use Blomstra\SupportAi\Message\Factory as Message;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenAI\Client;

class Agent
{
    protected bool $canMention = false;
    protected Model $model;

    public function __construct(
        public readonly User $user,
        public readonly ?string $persona = null,
        public readonly ?string $moderatingBehaviour = null,
        protected ?Client $client = null,
        string $model = null,
    ) {
        $this->model = $model ? Model::from($model) : Model::gpt_3_5_turbo;
    }

    public function operational(): bool
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
        if ($persona = Message::buildPersona($this)) {
            $messages->prepend($persona);
        }

        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'user' => "user-$post->user_id"
        ]);

        if (empty($response->choices)) return;

        $choice = Arr::first($response->choices);
        $respond = $choice->message->content;

        if (Str::startsWith($respond, 'FLAG: ')) {
            $flag = new Flag(
                Str::after($respond, 'FLAG: '),
                $post
            );

            $flag();
        } else {
            $reply = new Reply(
                reply: $respond,
                shouldMention: $this->canMention,
                inReplyTo: $post
            );

            CommentPost::reply(
                discussionId: $post->discussion_id,
                content: $reply(),
                userId: $this->user->id,
                ipAddress: '127.0.0.1',
                actor: $this->user
            )->save();
        }
    }

    public function toggleMentioning(bool $mention = true): self
    {
        $this->canMention = $mention;

        return $this;
    }

    public function canMention(): bool
    {
        return $this->canMention;
    }
}

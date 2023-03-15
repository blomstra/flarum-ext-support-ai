<?php

namespace Blomstra\SupportAi\Agent;

use Blomstra\SupportAi\Agent;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\Guest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Message implements Arrayable
{
    public static ?Agent $agent = null;

    public function __construct(
        public Role $role,
        public string $content
    )
    {}

    public function toArray()
    {
        return [
            'role' => $this->role->name,
            'content' => str_replace(['\r\n', '\n', PHP_EOL], '', $this->content)
        ];
    }

    public static function buildFromPersona(Agent $agent)
    {
        return new self(
            Role::assistant,
            $agent->persona
        );
    }

    public static function buildFromPost(Post $post)
    {
        return new self(
            static::$agent->is($post->user) ? Role::assistant : Role::user,
            $post->content
        );
    }

    /**
     * @param Post $post
     * @return Collection<Message>
     */
    public static function buildFromHistory(Post $post): Collection
    {
        $messages = Collection::make();

        if ($post->discussion->firstPost) {
            $messages->push(self::buildFromPost($post->discussion->firstPost));
        }

        if ($post->discussion->comment_count < 20) {
            $post->discussion->comments()
                ->whereVisibleTo(new Guest)
                ->whereKeyNot($post)
                ->when($post->discussion->firstPost, fn ($query, $firstPost) => $query->whereKeyNot($firstPost))
                ->each(fn (CommentPost $comment) => $messages->push(self::buildFromPost($comment)));
        } else {
            static::buildFromMentions($post)
                ->each(fn (CommentPost $comment) => $messages->push(self::buildFromPost($comment)));
        }

        return $messages;
    }

    protected static function buildFromMentions(Post $post): Collection
    {
        $collect = $post->mentionsPosts ?? Collection::make();

        $collect->each(fn (Post $post) => $collect->merge($post->mentionsPost ?? Collection::make()));

        return $collect->unique();
    }
}

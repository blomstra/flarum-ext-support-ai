<?php

namespace Blomstra\SupportAi\Message;

use Blomstra\SupportAi\Agent;
use Blomstra\SupportAi\Agent\Role;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\Guest;
use Illuminate\Support\Collection;

class Factory
{
    protected static ?Agent $agent = null;

    public static function buildPersona(Agent $agent): ?Message
    {
        $persona = $agent->persona;
        $moderator = $agent->moderatingBehaviour;

        if (! $persona && ! $moderator) return null;

        $instructions = '';

        // Combined instructions needs precise instructions
        if ($persona && $moderator) {
            $instructions .= <<<EOM
You are tasked with reviewing posts made by people on a community. Your task is twofold, on one side you will review
the text based on moderation instructions and on the other hand you will fulfill the role of an assistant.
EOM;
            $moderator = "here follow the instructions as a content moderator, in case you consider the content of the user to breach these instructions reply with 'FLAG: ' and the reason you consider this to breach the instructions: $moderator";
            $persona = "Here follow the instructions as an assistant: $persona";
        }

        $instructions .= <<<EOM
$moderator

$persona
EOM;
        
        return new Message(
            Role::assistant,
            $instructions
        );
    }

    public static function buildFromPost(Post $post): Message
    {
        return new Message(
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

    public static function setAgent(Agent $agent): void
    {
        static::$agent = $agent;
    }
}

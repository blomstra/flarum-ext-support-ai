<?php

namespace Blomstra\SupportAi\Agent;

use Flarum\Discussion\Discussion;
use Flarum\Tags\Tag;
use Illuminate\Support\Collection;

class Training
{
    protected ?Tag $tag = null;
    protected bool $bestAnswered = false;

    public function usingTag(string|Tag $tag): self
    {
        $this->tag = $tag instanceof Tag
            ? $tag
            : Tag::query()->where('slug', $tag)->first();

        return $this;
    }

    public function onlyWithBestAnswers(): self
    {
        $this->bestAnswered = true;

        return $this;
    }

    public function data(): Collection
    {
        return Discussion::query()
            ->with('firstPost', 'bestAnswerPost')
//            ->whereHas('tags', fn (Builder $query) => $query->where('tags.id', $this->tag))
            ->whereNotNull('discussions.best_answer_post_id')
            ->latest('discussions.created_at')
            ->limit(50)
            ->get()
            ->map(function (Discussion $discussion) {
                return [
                    $discussion->id,
                    $discussion->firstPost->content,
                    $discussion->bestAnswerPost->content
                ];
            });
    }
}

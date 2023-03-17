<?php

namespace Blomstra\SupportAi\Agent;

use Blomstra\SupportAi\Event\Flagging;
use Carbon\Carbon;
use Flarum\Flags\Event\Created;
use Flarum\Flags\Flag as FlagPost;
use Flarum\Post\Post;

class Flag extends Action
{
    public function __construct(
        public string $reason,
        protected readonly Post $flagged,
    )
    {}

    public function __invoke()
    {
        static::$events->dispatch(new Flagging($this));

        FlagPost::unguard();

        $flag = FlagPost::firstOrNew([
            'post_id' => $this->flagged->id,
            'user_id' => static::$agent->user->id
        ]);

        $flag->post_id = $this->flagged->id;
        $flag->user_id = static::$agent->user->id;
        $flag->type = 'user';
        $flag->reason = 'Flagged by support-ai';
        $flag->reason_detail = $this->reason;
        $flag->created_at = Carbon::now();

        $flag->save();

        static::$events->dispatch(new Created($flag, static::$agent->user, []));
    }
}

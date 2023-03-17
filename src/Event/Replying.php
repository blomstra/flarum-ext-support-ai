<?php

namespace Blomstra\SupportAi\Event;

use Blomstra\SupportAi\Agent\Reply;

class Replying
{
    public function __construct(
        public Reply $reply,
        public string $message
    )
    {}
}

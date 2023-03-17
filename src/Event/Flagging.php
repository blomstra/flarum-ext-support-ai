<?php

namespace Blomstra\SupportAi\Event;

use Blomstra\SupportAi\Agent\Flag;

class Flagging
{
    public function __construct(
        public Flag $flag
    )
    {}
}

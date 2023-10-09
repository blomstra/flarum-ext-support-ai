<?php

namespace Flarum\Ai\Models\Concerns;

enum Authorization: string
{
    case allow = 'allow';
    case ignore = 'ignore';
}

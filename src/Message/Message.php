<?php

namespace Blomstra\SupportAi\Message;

use Blomstra\SupportAi\Agent\Role;
use Illuminate\Contracts\Support\Arrayable;

class Message implements Arrayable
{
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
}

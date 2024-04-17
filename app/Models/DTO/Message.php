<?php

namespace App\Models\DTO;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationTypeEnum;

class Message
{
    public function __construct(
        public NotificationChannelEnum $channel,
        public NotificationTypeEnum $type,
        public string $body,
    ) {}
}
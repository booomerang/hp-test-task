<?php

namespace App\Enums;

enum NotificationChannelEnum : string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case SLACK = 'slack';
    case TEAMS = 'teams';
    case WEBHOOK = 'webhook';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

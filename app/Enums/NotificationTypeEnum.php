<?php

namespace App\Enums;

enum NotificationTypeEnum : string
{
    case BIRTHDAY_DIGEST = 'birthday_digest';
    case REMINDER = 'reminder';
    case INVITATION = 'invitation';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

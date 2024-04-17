<?php

namespace App\Console\Commands;

use RuntimeException;
use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationTypeEnum;
use App\Notifications\BirthdayDigestNotification;
use App\Notifications\ReminderNotification;
use App\Notifications\InvitationNotification;
use App\Services\NotificationService;
use App\Models\DTO\Message as MessageDTO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-message {channel} {type} {body}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification message';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $channel = $this->argument('channel');
        $type = $this->argument('type');
        $body = $this->argument('body');

        $channel = NotificationChannelEnum::tryFrom($channel);
        $type = NotificationTypeEnum::tryFrom($type);

        if (!$channel) {
            throw new RuntimeException('Unknown channel provided.');
        }
        if (!$type) {
            throw new RuntimeException('Unknown type provided.');
        }

        $notification = $this->createNotification(new MessageDTO($channel, $type, $body));
        $notificationService->sendAnonymously($notification);
    }

    protected function createNotification(MessageDTO $message)
    {
        return match ($message->type) {
            NotificationTypeEnum::BIRTHDAY_DIGEST => new BirthdayDigestNotification($message),
            NotificationTypeEnum::REMINDER => new ReminderNotification($message),
            NotificationTypeEnum::INVITATION => new InvitationNotification($message),
        };
    }
}

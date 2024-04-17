<?php

namespace App\Notifications;

use App\Enums\NotificationChannelEnum;
use App\Models\DTO\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class GenericNotificationMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<string>
     */
    public function via(object $notifiable): string
    {
        return match ($this->message->channel) {
            NotificationChannelEnum::EMAIL => 'mail',
            NotificationChannelEnum::SMS => 'vonage',
            NotificationChannelEnum::SLACK => 'slack',
            NotificationChannelEnum::TEAMS => MicrosoftTeamsChannel::class,
            NotificationChannelEnum::WEBHOOK => WebhookChannel::class,
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
                    ->content('Your SMS message content');
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->text('Your Slack message text')
            ->headerBlock('Message from GenericNotificationMessage');
    }

    /**
     * Get the MicrosoftTeams representation of the notification.
     */
    public function toMicrosoftTeams($notifiable)
    {
        return MicrosoftTeamsMessage::create()
            ->to(config('services.microsoft_teams.sales_url'))
            ->type('success')
            ->title('Your MicrosoftTeamsMessage message title')
            ->content('Your MicrosoftTeamsMessage message text')
            ->button('Give a thumb up', 'https://foo.bar/users/123');
    }

    /**
     * Get the Webhook representation of the notification.
     */
    public function toWebhook($notifiable)
    {
        return WebhookMessage::create()
            ->data([
               'payload' => [
                   'webhook' => 'data'
               ]
            ])
            ->userAgent("Custom-User-Agent")
            ->header('X-Custom', 'Custom-Header');
    }

    /**
     * return array<string>
     */
    public function viaConnections(): array
    {
        return [
            'mail' => 'sync',
            'database' => 'sync',
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'mail-queue',
            'vonage' => 'sms-queue',
            'slack' => 'slack-queue',
            MicrosoftTeamsChannel::class => 'teams-queue',
            WebhookChannel::class => 'webhook-queue',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'channel' => $this->message->channel,
            'type' => $this->message->type,
            'body' => $this->message->body,
        ];
    }

    /**
     * Method will be called when job is failed
     */
    public function failed(\Throwable $e): void
    {
        Log::channel($this->queue)->info(sprintf('Notification %s sending has failed. Exception message: %s.', $this->id, $e->getMessage()));
    }
}

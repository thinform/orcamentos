<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class QuoteStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quote;
    protected $oldStatus;
    protected $statusText = [
        'pending' => 'Pendente',
        'approved' => 'Aprovado',
        'rejected' => 'Rejeitado'
    ];

    public function __construct(Quote $quote, string $oldStatus)
    {
        $this->quote = $quote;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('quotes.show', $this->quote);

        return (new MailMessage)
            ->subject('Status do Orçamento Alterado - #' . $this->quote->quote_number)
            ->greeting('Olá!')
            ->line('O status do orçamento #' . $this->quote->quote_number . ' foi alterado.')
            ->line('De: ' . $this->statusText[$this->oldStatus])
            ->line('Para: ' . $this->statusText[$this->quote->status])
            ->action('Ver Orçamento', $url)
            ->line('Obrigado por usar nosso sistema!');
    }

    public function toWhatsApp($notifiable): string
    {
        $emoji = [
            'pending' => '⏳',
            'approved' => '✅',
            'rejected' => '❌'
        ];

        return "{$emoji[$this->quote->status]} *Status do Orçamento Alterado*\n\n" .
            "Número: #{$this->quote->quote_number}\n" .
            "Cliente: {$this->quote->client->name}\n" .
            "De: {$this->statusText[$this->oldStatus]}\n" .
            "Para: {$this->statusText[$this->quote->status]}\n\n" .
            "Acesse o sistema para mais detalhes.";
    }

    public function toArray($notifiable): array
    {
        return [
            'quote_id' => $this->quote->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->quote->status,
            'message' => 'Status do orçamento alterado: #' . $this->quote->quote_number,
        ];
    }
} 
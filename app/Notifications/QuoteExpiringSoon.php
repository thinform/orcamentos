<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class QuoteExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quote;
    protected $daysUntilExpiry;

    public function __construct(Quote $quote, int $daysUntilExpiry)
    {
        $this->quote = $quote;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('quotes.show', $this->quote);

        return (new MailMessage)
            ->subject('Orçamento Próximo do Vencimento - #' . $this->quote->quote_number)
            ->greeting('Olá!')
            ->line('O orçamento #' . $this->quote->quote_number . ' está próximo do vencimento.')
            ->line('Cliente: ' . $this->quote->client->name)
            ->line('Data de validade: ' . $this->quote->validity->format('d/m/Y'))
            ->line('Dias restantes: ' . $this->daysUntilExpiry)
            ->action('Ver Orçamento', $url)
            ->line('Obrigado por usar nosso sistema!');
    }

    public function toWhatsApp($notifiable): string
    {
        return "⚠️ *Orçamento Próximo do Vencimento*\n\n" .
            "Número: #{$this->quote->quote_number}\n" .
            "Cliente: {$this->quote->client->name}\n" .
            "Validade: {$this->quote->validity->format('d/m/Y')}\n" .
            "Dias restantes: {$this->daysUntilExpiry}\n\n" .
            "Acesse o sistema para mais detalhes.";
    }

    public function toArray($notifiable): array
    {
        return [
            'quote_id' => $this->quote->id,
            'days_until_expiry' => $this->daysUntilExpiry,
            'message' => 'Orçamento próximo do vencimento: #' . $this->quote->quote_number,
        ];
    }
} 
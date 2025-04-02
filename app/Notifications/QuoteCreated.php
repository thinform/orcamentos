<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class QuoteCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function via($notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('quotes.show', $this->quote);

        return (new MailMessage)
            ->subject('Novo Orçamento Criado - #' . $this->quote->quote_number)
            ->greeting('Olá!')
            ->line('Um novo orçamento foi criado para ' . $this->quote->client->name)
            ->line('Número do orçamento: ' . $this->quote->quote_number)
            ->line('Valor total: ' . format_currency($this->quote->total))
            ->action('Ver Orçamento', $url)
            ->line('Obrigado por usar nosso sistema!');
    }

    public function toWhatsApp($notifiable): string
    {
        return "🆕 *Novo Orçamento Criado*\n\n" .
            "Número: #{$this->quote->quote_number}\n" .
            "Cliente: {$this->quote->client->name}\n" .
            "Valor Total: " . format_currency($this->quote->total) . "\n\n" .
            "Acesse o sistema para mais detalhes.";
    }

    public function toArray($notifiable): array
    {
        return [
            'quote_id' => $this->quote->id,
            'message' => 'Novo orçamento criado: #' . $this->quote->quote_number,
        ];
    }
} 
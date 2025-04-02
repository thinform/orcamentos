<?php

namespace App\Console\Commands;

use App\Models\Quote;
use App\Notifications\QuoteExpiringSoon;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiringQuotes extends Command
{
    protected $signature = 'quotes:check-expiring';
    protected $description = 'Verifica orçamentos próximos do vencimento e envia notificações';

    public function handle()
    {
        $this->info('Verificando orçamentos próximos do vencimento...');

        // Busca orçamentos que vencem em 3 dias ou menos
        $quotes = Quote::where('status', 'pending')
            ->whereDate('validity', '>=', Carbon::today())
            ->whereDate('validity', '<=', Carbon::today()->addDays(3))
            ->get();

        foreach ($quotes as $quote) {
            $daysUntilExpiry = Carbon::today()->diffInDays($quote->validity, false);
            
            // Notifica o usuário que criou o orçamento
            $quote->user->notify(new QuoteExpiringSoon($quote, $daysUntilExpiry));

            $this->info("Notificação enviada para orçamento #{$quote->quote_number} - Vence em {$daysUntilExpiry} dias");
        }

        $this->info('Verificação concluída!');
    }
} 
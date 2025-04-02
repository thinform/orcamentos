<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckQuoteStatus extends Command
{
    protected $signature = 'quotes:check-status';
    protected $description = 'Verifica e atualiza o status dos orçamentos com mais de 72 horas sem aprovação';

    public function handle()
    {
        $this->info('Verificando orçamentos...');

        $quotes = Quote::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subHours(72))
            ->get();

        foreach ($quotes as $quote) {
            $quote->update(['status' => 'canceled']);
            $this->line("Orçamento #{$quote->quote_number} foi cancelado automaticamente.");
        }

        $this->info("Verificação concluída. {$quotes->count()} orçamentos foram cancelados.");
    }
} 
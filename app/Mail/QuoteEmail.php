<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $quote;
    public $pdfPath;

    public function __construct(Quote $quote, $pdfPath)
    {
        $this->quote = $quote;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject("Orçamento #{$this->quote->quote_number}")
                    ->view('emails.quote')
                    ->attach($this->pdfPath, [
                        'as' => "orcamento_{$this->quote->quote_number}.pdf",
                        'mime' => 'application/pdf'
                    ]);
    }
} 
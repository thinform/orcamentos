<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class QuoteHistory extends Component
{
    public $history;

    /**
     * Create a new component instance.
     */
    public function __construct($history = null)
    {
        $this->history = $history ?? collect();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.quote-history');
    }
}

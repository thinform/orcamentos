<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Client;
use App\Models\Category;

class QuoteFilter extends Component
{
    public $clients;
    public $categories;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->clients = Client::orderBy('name')->get();
        $this->categories = Category::orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.quote-filter');
    }
}

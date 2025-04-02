<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\QuoteCreated' => [
            'App\Listeners\SendQuoteCreatedNotification',
            'App\Listeners\NotifyAdminAboutNewQuote',
        ],
        'App\Events\QuoteUpdated' => [
            'App\Listeners\SendQuoteUpdatedNotification',
        ],
        'App\Events\QuoteApproved' => [
            'App\Listeners\SendQuoteApprovedNotification',
        ],
        'App\Events\QuoteRejected' => [
            'App\Listeners\SendQuoteRejectedNotification',
        ],
        'App\Events\ProductLowStock' => [
            'App\Listeners\SendProductLowStockNotification',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\SendWelcomeEmail',
            'App\Listeners\NotifyAdminAboutNewUser',
        ],
        'App\Events\ClientCreated' => [
            'App\Listeners\SendClientWelcomeEmail',
            'App\Listeners\NotifyAdminAboutNewClient',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 
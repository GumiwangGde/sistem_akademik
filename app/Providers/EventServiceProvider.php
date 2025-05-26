<?php

namespace App\Providers;

use App\Models\MasterMatakuliah;

use App\Observers\MasterMatakuliahObserver;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        MasterMatakuliah::class => [MasterMatakuliahObserver::class],
    ];

    public function boot(): void
    {
        MasterMatakuliah::observe(MasterMatakuliahObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

<?php

namespace App\Providers;

use App\Models\Cita;
use App\Policies\CitaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Cita::class => CitaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

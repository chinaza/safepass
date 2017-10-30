<?php

namespace App\Providers;

use App\TeamUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('edit-password', function($user, $team) {
          $teamUser = TeamUser::where('user_id', $user->id)
          ->where('team_id', $team->id)
          ->first();
          return $teamUser->role == 'admin' || $teamUser->role == 'contributor';
        });

        Gate::define('get-password', function($user, $team) {
          $teamUser = TeamUser::where('user_id', $user->id)
          ->where('team_id', $team->id)
          ->first();
          return $teamUser->role == 'admin' || $teamUser->role == 'contributor' || $teamUser->role == 'member';
        });
    }
}

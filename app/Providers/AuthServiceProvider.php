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
      return $teamUser->role == 'admin' || $teamUser->role == 'contributor' || $teamUser->role == 'team_owner' || $teamUser->role == 'company_owner';
    });

    Gate::define('manage-users', function($user, $teamId, $userRole) {
      $subject = TeamUser::where('user_id', $user->id)
      ->where('team_id', $teamId)
      ->first();

      switch ($userRole)
      {
        case 'member':
        case 'contributor':
        return $subject->role == 'company_owner' || $subject->role == 'team_owner' || $subject->role == 'admin';
        break;
        case 'admin':
        return $subject->role == 'company_owner' || $subject->role == 'team_owner';
        break;
        case 'team_owner':
        return $subject->role == 'company_owner';
        break;
      }

      return false;
    });
  }
}

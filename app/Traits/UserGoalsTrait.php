<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserGoalsTrait
{
  public static function bootUserGoalsTrait()
  {
    if (!auth()->user()->roles->contains(1)) {
      static::addGlobalScope('projects_of_user_initiative', function (Builder $builder) {
        $builder->has('goalProjects');
      });
    }
  }
}

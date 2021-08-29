<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserProjectsTrait
{
  public static function bootUserProjectsTrait()
  {
    if (!auth()->user()->roles->contains(1)) {
      static::addGlobalScope('projects_of_user_initiative', function (Builder $builder) {
        $builder->join('initiatives as i', function ($join) {
          $join->on('projects.id', '=', 'i.project_id');
        })->join('initiative_user as iu', function ($join) {
          $join->on('i.id', '=', 'iu.initiative_id');
        })->where('iu.user_id', auth()->user()->id);
      });
    }
  }
}

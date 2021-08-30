<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserProjectsTrait
{
  public static function bootUserProjectsTrait()
  {
    if (!auth()->user()->roles->contains(1)) {
      /*static::addGlobalScope('projects_of_user_initiative', function (Builder $builder) { // Dose not retrieve goals collection
        $builder->join('initiatives as i', function ($join) {
          $join->on('projects.id', '=', 'i.project_id');
        })->join('initiative_user as iu', function ($join) {
          $join->on('i.id', '=', 'iu.initiative_id');
        })->where('iu.user_id', auth()->user()->id);
      });*/

      /* projectInitiatives (Not Working)
      static::addGlobalScope('projects_of_user_initiative', function (Builder $builder) {
        $builder->whereHas('projectInitiatives',function ($query){
        $query->whereHas('users', function ($query) {
          $query->where('id', '=', auth()->user()->id);
        });
      });
      */
    }
  }
}

<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserInitiativeTrait
{
  public static function bootUserInitiativeTrait()
  {
    if (!auth()->user()->roles->contains(1)) {
      static::addGlobalScope('user_initiative', function (Builder $builder) {
        /*$builder->join('initiative_user as iu', function ($join) {
          $join->on('initiatives.id', '=', 'iu.initiative_id');
        })->where('iu.user_id', auth()->user()->id);*/
        $builder->whereHas('users',function ($query) {
            $query->where('id', '=', auth()->user()->id);
        });
      });
    }
  }
}

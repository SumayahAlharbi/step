<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \DateTimeInterface;
use \LaravelArchivable\Archivable;

class Initiative extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, Archivable;

    public $table = 'initiatives';

    protected $appends = [
        'attachments',
    ];

    public static $searchable = [
        'title',
        'description',
        'kpi_description',
        'attachments',
    ];

    const STATUS_SELECT = [
        'Accomplished'     => 'Accomplished',
        'Not Accomplished' => 'Not Accomplished',
    ];

    protected $dates = [
        'created_at',
        'kpi_previous_date',
        'kpi_current_date',
        'kpi_target_date',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'created_at',
        'kpi_description',
        'kpi_previous',
        'kpi_previous_date',
        'kpi_current',
        'kpi_current_date',
        'kpi_target',
        'kpi_target_date',
        'status',
        'why_if_not_accomplished',
        'dod_comment',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function initiativeActionPlans()
    {
        return $this->hasMany(ActionPlan::class, 'initiative_id', 'id');
    }

    public function initiativeRisks()
    {
        return $this->hasMany(Risk::class, 'initiative_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getKpiPreviousDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setKpiPreviousDateAttribute($value)
    {
        $this->attributes['kpi_previous_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getKpiCurrentDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setKpiCurrentDateAttribute($value)
    {
        $this->attributes['kpi_current_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getKpiTargetDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setKpiTargetDateAttribute($value)
    {
        $this->attributes['kpi_target_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}

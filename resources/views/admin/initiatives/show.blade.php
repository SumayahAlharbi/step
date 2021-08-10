@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.initiative.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.initiatives.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.id') }}
                        </th>
                        <td>
                            {{ $initiative->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.title') }}
                        </th>
                        <td>
                            {{ $initiative->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.description') }}
                        </th>
                        <td>
                            {!! $initiative->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.project') }}
                        </th>
                        <td>
                            {{ $initiative->project->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.created_at') }}
                        </th>
                        <td>
                            {{ $initiative->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_description') }}
                        </th>
                        <td>
                            {!! $initiative->kpi_description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_previous') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_previous }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_previous_date') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_previous_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_current') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_current }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_current_date') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_current_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_target') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_target }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.kpi_target_date') }}
                        </th>
                        <td>
                            {{ $initiative->kpi_target_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Initiative::STATUS_SELECT[$initiative->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.why_if_not_accomplished') }}
                        </th>
                        <td>
                            {!! $initiative->why_if_not_accomplished !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.dod_comment') }}
                        </th>
                        <td>
                            {!! $initiative->dod_comment !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.attachments') }}
                        </th>
                        <td>
                            @foreach($initiative->attachments as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    @can('audit_log_access')
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.user') }}
                        </th>
                        <td>
                            @foreach($initiative->users as $key => $user)
                                <span class="label label-info">{{ $user->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    @endcan
                    <tr>
                        <th>
                            {{ trans('cruds.initiative.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $initiative->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.initiatives.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#initiative_action_plans" role="tab" data-toggle="tab">
                {{ trans('cruds.actionPlan.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#initiative_risks" role="tab" data-toggle="tab">
                {{ trans('cruds.risk.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="initiative_action_plans">
            @includeIf('admin.initiatives.relationships.initiativeActionPlans', ['actionPlans' => $initiative->initiativeActionPlans])
        </div>
        <div class="tab-pane" role="tabpanel" id="initiative_risks">
            @includeIf('admin.initiatives.relationships.initiativeRisks', ['risks' => $initiative->initiativeRisks])
        </div>
    </div>
</div>

@endsection

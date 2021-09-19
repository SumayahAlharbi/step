@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.goal.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.goals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.id') }}
                        </th>
                        <td>
                            {{ $goal->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.title') }}
                        </th>
                        <td>
                            {{ $goal->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.description') }}
                        </th>
                        <td>
                            {!! $goal->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.created_at') }}
                        </th>
                        <td>
                            {{ $goal->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.user') }}
                        </th>
                        <td>
                            @foreach($goal->users as $key => $user)
                                <span class="label label-info">{{ $user->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.strategic_plan') }}
                        </th>
                        <td>
                            {{ $goal->strategic_plan->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.goal.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $goal->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.goals.index') }}">
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
            <a class="nav-link" href="#goal_projects" role="tab" data-toggle="tab">
                {{ trans('cruds.project.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="goal_projects">
            @includeIf('admin.goals.relationships.goalProjects', ['projects' => $goal->goalProjects])
        </div>
    </div>
</div>

@endsection

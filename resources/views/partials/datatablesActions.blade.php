@if(isset($viewGate))
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ trans('global.view') }}
    </a>
@endif
@if(isset($editGate))
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
@endif
@if(isset($deleteGate))
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
    </form>
@endif
@if(isset($archiveGate))
    <a class="btn btn-xs btn-warning" href="{{ route('admin.' . $crudRoutePart . '.archive', $row->id) }}">
        {{ trans('global.archive') }}
    </a>
@endif
@if(isset($restoreGate))
    <a class="btn btn-xs btn-warning" href="{{ route('admin.' . $crudRoutePart . '.restore' , $row->id) }}">
        {{ trans('global.restore') }}
    </a>
@endif

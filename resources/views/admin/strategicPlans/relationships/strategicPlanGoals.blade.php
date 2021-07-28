@can('goal_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.goals.create', ['id'=>$strategicPlan->id]) }}">
                {{ trans('global.add') }} {{ trans('cruds.goal.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.goal.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-strategicPlanGoals">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.goal.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.goal.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.goal.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.goal.fields.strategic_plan') }}
                        </th>
                        <th>
                            {{ trans('cruds.goal.fields.team') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goals as $key => $goal)
                        <tr data-entry-id="{{ $goal->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $goal->id ?? '' }}
                            </td>
                            <td>
                                {{ $goal->title ?? '' }}
                            </td>
                            <td>
                                @foreach($goal->users as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $goal->strategic_plan->name ?? '' }}
                            </td>
                            <td>
                                {{ $goal->team->name ?? '' }}
                            </td>
                            <td>
                                @can('goal_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.goals.show', $goal->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('goal_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.goals.edit', $goal->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('goal_delete')
                                    <form action="{{ route('admin.goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('goal_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.goals.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-strategicPlanGoals:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection

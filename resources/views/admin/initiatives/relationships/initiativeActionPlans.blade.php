@can('action_plan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.action-plans.create', ['id'=>$initiative->id]) }}">
                {{ trans('global.add') }} {{ trans('cruds.actionPlan.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.actionPlan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-initiativeActionPlans">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.initiative') }}
                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.approval') }}
                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.actionPlan.fields.updated_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actionPlans as $key => $actionPlan)
                        <tr data-entry-id="{{ $actionPlan->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $actionPlan->id ?? '' }}
                            </td>
                            <td>
                                {{ $actionPlan->title ?? '' }}
                            </td>
                            <td>
                                {{ $actionPlan->initiative->title ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\ActionPlan::APPROVAL_SELECT[$actionPlan->approval] ?? '' }}
                            </td>
                            <td>
                                @foreach($actionPlan->users as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $actionPlan->updated_at ?? '' }}
                            </td>
                            <td>
                                @can('action_plan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.action-plans.show', $actionPlan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('action_plan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.action-plans.edit', $actionPlan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('action_plan_delete')
                                    <form action="{{ route('admin.action-plans.destroy', $actionPlan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('action_plan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.action-plans.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-initiativeActionPlans:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection

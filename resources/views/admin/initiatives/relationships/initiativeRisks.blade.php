@can('risk_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.risks.create', ['id'=>$initiative->id]) }}">
                {{ trans('global.add') }} {{ trans('cruds.risk.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.risk.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-initiativeRisks">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.probability') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.impact') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.gross') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.action') }}
                        </th>
                        <th>
                            {{ trans('cruds.risk.fields.initiative') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($risks as $key => $risk)
                        <tr data-entry-id="{{ $risk->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $risk->id ?? '' }}
                            </td>
                            <td>
                                {{ $risk->title ?? '' }}
                            </td>
                            <td>
                                {{ $risk->probability ?? '' }}
                            </td>
                            <td>
                                {{ $risk->impact ?? '' }}
                            </td>
                            <td>
                                {{ $risk->gross ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Risk::ACTION_SELECT[$risk->action] ?? '' }}
                            </td>
                            <td>
                                {{ $risk->initiative->title ?? '' }}
                            </td>
                            <td>
                                @can('risk_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.risks.show', $risk->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('risk_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.risks.edit', $risk->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('risk_delete')
                                    <form action="{{ route('admin.risks.destroy', $risk->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('risk_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.risks.massDestroy') }}",
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
  let table = $('.datatable-initiativeRisks:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection

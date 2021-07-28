@extends('layouts.admin')
@section('content')
<h5> {{ trans('cruds.risk.title_singular') }} {{ trans('global.list') }}</h5>
<div class="card">
    <div class="card-header">
      @can('risk_create')
          <div style="margin-bottom: 10px;" class="row">
              <div class="col-lg-12">
                  <a class="btn btn-success" href="{{ route('admin.risks.create') }}">
                      {{ trans('global.add') }} {{ trans('cruds.risk.title_singular') }}
                  </a>
                  <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                      {{ trans('global.app_csvImport') }}
                  </button>
                  @include('csvImport.modal', ['model' => 'Risk', 'route' => 'admin.risks.parseCsvImport'])
              </div>
          </div>
      @endcan
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Risk">
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('risk_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.risks.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.risks.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'title', name: 'title' },
{ data: 'probability', name: 'probability' },
{ data: 'impact', name: 'impact' },
{ data: 'gross', name: 'gross' },
{ data: 'action', name: 'action' },
{ data: 'initiative_title', name: 'initiative.title' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Risk').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection

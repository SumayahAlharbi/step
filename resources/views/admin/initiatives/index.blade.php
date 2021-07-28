@extends('layouts.admin')
@section('content')
<h5>{{ trans('cruds.initiative.title_singular') }} {{ trans('global.list') }}</h5>
<div class="card">
    <div class="card-header">
      @can('initiative_create')
          <div style="margin-bottom: 10px;" class="row">
              <div class="col-lg-12">
                  <a class="btn btn-success" href="{{ route('admin.initiatives.create') }}">
                      {{ trans('global.add') }} {{ trans('cruds.initiative.title_singular') }}
                  </a>
                  <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                      {{ trans('global.app_csvImport') }}
                  </button>
                  @include('csvImport.modal', ['model' => 'Initiative', 'route' => 'admin.initiatives.parseCsvImport'])
              </div>
          </div>
      @endcan
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Initiative">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.project') }}
                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.initiative.fields.updated_at') }}
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
@can('initiative_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.initiatives.massDestroy') }}",
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
    ajax: "{{ route('admin.initiatives.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'title', name: 'title' },
{ data: 'project_title', name: 'project.title' },
{ data: 'status', name: 'status' },
{ data: 'user', name: 'users.name' },
{ data: 'updated_at', name: 'updated_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Initiative').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection

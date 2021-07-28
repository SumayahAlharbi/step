@extends('layouts.admin')
@section('content')
<h5> {{ trans('cruds.strategicPlan.title_singular') }} {{ trans('global.list') }} </h5>
<div class="card">
    <div class="card-header">
      @can('strategic_plan_create')
          <div style="margin-bottom: 10px;" class="row">
              <div class="col-lg-12">
                  <a class="btn btn-success" href="{{ route('admin.strategic-plans.create') }}">
                      {{ trans('global.add') }} {{ trans('cruds.strategicPlan.title_singular') }}
                  </a>
                  <button class="btn btn-info" data-toggle="modal" data-target="#csvImportModal">
                      {{ trans('global.app_csvImport') }}
                  </button>
                  @include('csvImport.modal', ['model' => 'StrategicPlan', 'route' => 'admin.strategic-plans.parseCsvImport'])

                  <a class="btn btn-warning" href="{{ route('admin.strategic-plans.archiveList') }}">
                      {{ trans('global.archive') }}
                  </a>

              </div>
          </div>
      @endcan

    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StrategicPlan">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.strategicPlan.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.strategicPlan.fields.name') }}
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
@can('strategic_plan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.strategic-plans.massDestroy') }}",
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
    ajax: "{{ route('admin.strategic-plans.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-StrategicPlan').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection

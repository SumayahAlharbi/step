@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.initiative.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.initiatives.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.initiative.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.initiative.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.initiative.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $project)
                        @if (request()->get('id') == $id)
                                      <option value="{{ $id }}" selected>{{ $project }}</option>
                                @else
                                      <option value="{{ $id }}">{{ $project }}</option>
                                @endif
                    @endforeach
                </select>
                @if($errors->has('project'))
                    <div class="invalid-feedback">
                        {{ $errors->first('project') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_description">{{ trans('cruds.initiative.fields.kpi_description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('kpi_description') ? 'is-invalid' : '' }}" name="kpi_description" id="kpi_description">{!! old('kpi_description') !!}</textarea>
                @if($errors->has('kpi_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_previous">{{ trans('cruds.initiative.fields.kpi_previous') }}</label>
                <input class="form-control {{ $errors->has('kpi_previous') ? 'is-invalid' : '' }}" type="text" name="kpi_previous" id="kpi_previous" value="{{ old('kpi_previous', '') }}">
                @if($errors->has('kpi_previous'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_previous') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_previous_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_previous_date">{{ trans('cruds.initiative.fields.kpi_previous_date') }}</label>
                <input class="form-control date {{ $errors->has('kpi_previous_date') ? 'is-invalid' : '' }}" type="text" name="kpi_previous_date" id="kpi_previous_date" value="{{ old('kpi_previous_date') }}">
                @if($errors->has('kpi_previous_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_previous_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_previous_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_current">{{ trans('cruds.initiative.fields.kpi_current') }}</label>
                <input class="form-control {{ $errors->has('kpi_current') ? 'is-invalid' : '' }}" type="text" name="kpi_current" id="kpi_current" value="{{ old('kpi_current', '') }}">
                @if($errors->has('kpi_current'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_current') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_current_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_current_date">{{ trans('cruds.initiative.fields.kpi_current_date') }}</label>
                <input class="form-control date {{ $errors->has('kpi_current_date') ? 'is-invalid' : '' }}" type="text" name="kpi_current_date" id="kpi_current_date" value="{{ old('kpi_current_date') }}">
                @if($errors->has('kpi_current_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_current_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_current_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_target">{{ trans('cruds.initiative.fields.kpi_target') }}</label>
                <input class="form-control {{ $errors->has('kpi_target') ? 'is-invalid' : '' }}" type="text" name="kpi_target" id="kpi_target" value="{{ old('kpi_target', '') }}">
                @if($errors->has('kpi_target'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_target') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_target_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kpi_target_date">{{ trans('cruds.initiative.fields.kpi_target_date') }}</label>
                <input class="form-control date {{ $errors->has('kpi_target_date') ? 'is-invalid' : '' }}" type="text" name="kpi_target_date" id="kpi_target_date" value="{{ old('kpi_target_date') }}">
                @if($errors->has('kpi_target_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kpi_target_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.kpi_target_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.initiative.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Initiative::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="why_if_not_accomplished">{{ trans('cruds.initiative.fields.why_if_not_accomplished') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('why_if_not_accomplished') ? 'is-invalid' : '' }}" name="why_if_not_accomplished" id="why_if_not_accomplished">{!! old('why_if_not_accomplished') !!}</textarea>
                @if($errors->has('why_if_not_accomplished'))
                    <div class="invalid-feedback">
                        {{ $errors->first('why_if_not_accomplished') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.why_if_not_accomplished_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dod_comment">{{ trans('cruds.initiative.fields.dod_comment') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('dod_comment') ? 'is-invalid' : '' }}" name="dod_comment" id="dod_comment">{!! old('dod_comment') !!}</textarea>
                @if($errors->has('dod_comment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dod_comment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.dod_comment_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attachments">{{ trans('cruds.initiative.fields.attachments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attachments') ? 'is-invalid' : '' }}" id="attachments-dropzone">
                </div>
                @if($errors->has('attachments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attachments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.attachments_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="users">{{ trans('cruds.initiative.fields.user') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('users') ? 'is-invalid' : '' }}" name="users[]" id="users" multiple>
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ in_array($id, old('users', [])) ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('users'))
                    <div class="invalid-feedback">
                        {{ $errors->first('users') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.initiative.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/initiatives/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $initiative->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedAttachmentsMap = {}
Dropzone.options.attachmentsDropzone = {
    url: '{{ route('admin.initiatives.storeMedia') }}',
    maxFilesize: 30, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 30
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
      uploadedAttachmentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentsMap[file.name]
      }
      $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($initiative) && $initiative->attachments)
          var files =
            {!! json_encode($initiative->attachments) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection

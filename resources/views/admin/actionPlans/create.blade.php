@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.actionPlan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.action-plans.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.actionPlan.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="updates">{{ trans('cruds.actionPlan.fields.updates') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('updates') ? 'is-invalid' : '' }}" name="updates" id="updates">{!! old('updates') !!}</textarea>
                @if($errors->has('updates'))
                    <div class="invalid-feedback">
                        {{ $errors->first('updates') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.updates_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="initiative_id">{{ trans('cruds.actionPlan.fields.initiative') }}</label>
                <select class="form-control select2 {{ $errors->has('initiative') ? 'is-invalid' : '' }}" name="initiative_id" id="initiative_id" required>
                    @foreach($initiatives as $id => $initiative)
                        @if (request()->get('id') == $id)
                                      <option value="{{ $id }}" selected>{{ $initiative }}</option>
                                @else
                                      <option value="{{ $id }}">{{ $initiative }}</option>
                                @endif
                    @endforeach
                </select>
                @if($errors->has('initiative'))
                    <div class="invalid-feedback">
                        {{ $errors->first('initiative') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.initiative_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="risks">{{ trans('cruds.actionPlan.fields.risks') }}</label>
                <input class="form-control {{ $errors->has('risks') ? 'is-invalid' : '' }}" type="text" name="risks" id="risks" value="{{ old('risks', '') }}">
                @if($errors->has('risks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('risks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.risks_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resources">{{ trans('cruds.actionPlan.fields.resources') }}</label>
                <input class="form-control {{ $errors->has('resources') ? 'is-invalid' : '' }}" type="text" name="resources" id="resources" value="{{ old('resources', '') }}">
                @if($errors->has('resources'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resources') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.resources_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="start">{{ trans('cruds.actionPlan.fields.start') }}</label>
                <input class="form-control date {{ $errors->has('start') ? 'is-invalid' : '' }}" type="text" name="start" id="start" value="{{ old('start') }}">
                @if($errors->has('start'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.start_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="end">{{ trans('cruds.actionPlan.fields.end') }}</label>
                <input class="form-control date {{ $errors->has('end') ? 'is-invalid' : '' }}" type="text" name="end" id="end" value="{{ old('end') }}">
                @if($errors->has('end'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.end_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attachments">{{ trans('cruds.actionPlan.fields.attachments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attachments') ? 'is-invalid' : '' }}" id="attachments-dropzone">
                </div>
                @if($errors->has('attachments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attachments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actionPlan.fields.attachments_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="users">{{ trans('cruds.actionPlan.fields.user') }}</label>
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
                <span class="help-block">{{ trans('cruds.actionPlan.fields.user_helper') }}</span>
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
                xhr.open('POST', '/admin/action-plans/ckmedia', true);
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
                data.append('crud_id', '{{ $actionPlan->id ?? 0 }}');
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
    url: '{{ route('admin.action-plans.storeMedia') }}',
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
@if(isset($actionPlan) && $actionPlan->attachments)
          var files =
            {!! json_encode($actionPlan->attachments) !!}
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

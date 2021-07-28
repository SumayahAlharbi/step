@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.risk.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.risks.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">{{ trans('cruds.risk.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="probability">{{ trans('cruds.risk.fields.probability') }}</label>
                <input class="form-control {{ $errors->has('probability') ? 'is-invalid' : '' }}" type="number" name="probability" id="probability" value="{{ old('probability', '') }}" step="1">
                @if($errors->has('probability'))
                    <div class="invalid-feedback">
                        {{ $errors->first('probability') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.probability_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="impact">{{ trans('cruds.risk.fields.impact') }}</label>
                <input class="form-control {{ $errors->has('impact') ? 'is-invalid' : '' }}" type="number" name="impact" id="impact" value="{{ old('impact', '') }}" step="1">
                @if($errors->has('impact'))
                    <div class="invalid-feedback">
                        {{ $errors->first('impact') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.impact_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="gross">{{ trans('cruds.risk.fields.gross') }}</label>
                <input class="form-control {{ $errors->has('gross') ? 'is-invalid' : '' }}" type="number" name="gross" id="gross" value="{{ old('gross', '') }}" step="1">
                @if($errors->has('gross'))
                    <div class="invalid-feedback">
                        {{ $errors->first('gross') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.gross_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.risk.fields.action') }}</label>
                <select class="form-control {{ $errors->has('action') ? 'is-invalid' : '' }}" name="action" id="action">
                    <option value disabled {{ old('action', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Risk::ACTION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('action', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('action'))
                    <div class="invalid-feedback">
                        {{ $errors->first('action') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.action_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="action_details">{{ trans('cruds.risk.fields.action_details') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('action_details') ? 'is-invalid' : '' }}" name="action_details" id="action_details">{!! old('action_details') !!}</textarea>
                @if($errors->has('action_details'))
                    <div class="invalid-feedback">
                        {{ $errors->first('action_details') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.risk.fields.action_details_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="initiative_id">{{ trans('cruds.risk.fields.initiative') }}</label>
                <select class="form-control select2 {{ $errors->has('initiative') ? 'is-invalid' : '' }}" name="initiative_id" id="initiative_id">
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
                <span class="help-block">{{ trans('cruds.risk.fields.initiative_helper') }}</span>
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
                xhr.open('POST', '/admin/risks/ckmedia', true);
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
                data.append('crud_id', '{{ $risk->id ?? 0 }}');
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

@endsection

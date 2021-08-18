<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInitiativeRequest;
use App\Http\Requests\StoreInitiativeRequest;
use App\Http\Requests\UpdateInitiativeRequest;
use App\Models\Initiative;
use App\Models\Project;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class InitiativesController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('initiative_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Initiative::with(['project', 'users', 'team'])->select(sprintf('%s.*', (new Initiative)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'initiative_show';
                $editGate      = 'initiative_edit';
                $deleteGate    = 'initiative_delete';
                $crudRoutePart = 'initiatives';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->addColumn('description', function ($row) {
                return strip_tags(htmlspecialchars_decode($row->description));
            });
            $table->addColumn('project_id', function ($row) {
                return $row->project ? $row->project->id : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? Initiative::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('user', function ($row) {
                $labels = [];

                foreach ($row->users as $user) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $user->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'user']);

            return $table->make(true);
        }

        return view('admin.initiatives.index');
    }

    public function create()
    {
        abort_if(Gate::denies('initiative_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id');

        return view('admin.initiatives.create', compact('projects', 'users'));
    }

    public function store(StoreInitiativeRequest $request)
    {
        $initiative = Initiative::create($request->all());
        $initiative->users()->sync($request->input('users', []));

        foreach ($request->input('attachments', []) as $file) {
            $initiative->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $initiative->id]);
        }

        return redirect()->route('admin.initiatives.index');
    }

    public function edit(Initiative $initiative)
    {
        abort_if(Gate::denies('initiative_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id');

        $initiative->load('project', 'users', 'team');

        return view('admin.initiatives.edit', compact('projects', 'users', 'initiative'));
    }

    public function update(UpdateInitiativeRequest $request, Initiative $initiative)
    {
       if (Auth::user()->can('audit_log_access')) // if the admin requested the update
       {
        $initiative->update($request->all());
        $initiative->users()->sync($request->input('users', []));

        if (count($initiative->attachments) > 0) {
            foreach ($initiative->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $initiative->attachments->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $initiative->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }
        return view('admin.initiatives.show', compact('initiative'));
      }

      elseif (Auth::user()->can('initiative_edit')) { // if the responsible-role request the update
        $input = $request->all();
        if ($request->has('kpi_previous') OR $request->has('kpi_previous_date')
            OR $request->has('kpi_current') OR $request->has('kpi_current_date')
            OR $request->has('status') OR $request->has('why_if_not_accomplished')) {
          $initiative->update($input);
        }

        if (count($initiative->attachments) > 0) {
            foreach ($initiative->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $initiative->attachments->pluck('file_name')->toArray();
        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $initiative->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        return view('admin.initiatives.show', compact('initiative'));
      }
    }

    public function show(Initiative $initiative)
    {
        abort_if(Gate::denies('initiative_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiative->load('project', 'users', 'team', 'initiativeActionPlans', 'initiativeRisks');

        return view('admin.initiatives.show', compact('initiative'));
    }

    public function destroy(Initiative $initiative)
    {
        abort_if(Gate::denies('initiative_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiative->delete();

        return back();
    }

    public function massDestroy(MassDestroyInitiativeRequest $request)
    {
        Initiative::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('initiative_create') && Gate::denies('initiative_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Initiative();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

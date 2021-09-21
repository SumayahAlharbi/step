<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyActionPlanRequest;
use App\Http\Requests\StoreActionPlanRequest;
use App\Http\Requests\UpdateActionPlanRequest;
use App\Models\ActionPlan;
use App\Models\Initiative;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ActionPlansController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('action_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ActionPlan::with(['initiative', 'users'])->select(sprintf('%s.*', (new ActionPlan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'action_plan_show';
                $editGate      = 'action_plan_edit';
                $deleteGate    = 'action_plan_delete';
                $crudRoutePart = 'action-plans';

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
            $table->addColumn('initiative_title', function ($row) {
                return $row->initiative ? $row->initiative->title : '';
            });

            $table->editColumn('approval', function ($row) {
                return $row->approval ? ActionPlan::APPROVAL_SELECT[$row->approval] : '';
            });
            $table->editColumn('user', function ($row) {
                $labels = [];

                foreach ($row->users as $user) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $user->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'initiative', 'user']);

            return $table->make(true);
        }

        return view('admin.actionPlans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('action_plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiatives = Initiative::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id');

        return view('admin.actionPlans.create', compact('initiatives', 'users'));
    }

    public function store(StoreActionPlanRequest $request)
    {
        $actionPlan = ActionPlan::create($request->all());
        $actionPlan->users()->sync($request->input('users', []));

        foreach ($request->input('attachments', []) as $file) {
            $actionPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $actionPlan->id]);
        }

        return redirect()->route('admin.action-plans.index');
    }

    public function edit(ActionPlan $actionPlan)
    {
        abort_if(Gate::denies('action_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiatives = Initiative::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id');

        $actionPlan->load('initiative', 'users');

        return view('admin.actionPlans.edit', compact('initiatives', 'users', 'actionPlan'));
    }

    public function update(UpdateActionPlanRequest $request, ActionPlan $actionPlan)
    {
        $actionPlan->update($request->all());
        $actionPlan->users()->sync($request->input('users', []));

        if (count($actionPlan->attachments) > 0) {
            foreach ($actionPlan->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $actionPlan->attachments->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $actionPlan->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.action-plans.index');
    }

    public function show(ActionPlan $actionPlan)
    {
        abort_if(Gate::denies('action_plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actionPlan->load('initiative', 'users');

        return view('admin.actionPlans.show', compact('actionPlan'));
    }

    public function destroy(ActionPlan $actionPlan)
    {
        abort_if(Gate::denies('action_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actionPlan->delete();

        return back();
    }

    public function massDestroy(MassDestroyActionPlanRequest $request)
    {
        ActionPlan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('action_plan_create') && Gate::denies('action_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ActionPlan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

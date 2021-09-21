<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyGoalRequest;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use App\Models\StrategicPlan;
//use App\Models\Team;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GoalsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('goal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Goal::with(['users', 'strategic_plan'])->select(sprintf('%s.*', (new Goal)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'goal_show';
                $editGate      = 'goal_edit';
                $deleteGate    = 'goal_delete';
                $crudRoutePart = 'goals';

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
            $table->editColumn('description', function ($row) {
                return strip_tags(htmlspecialchars_decode($row->description));
            });
            $table->addColumn('strategic_plan_name', function ($row) {
                return $row->strategic_plan ? $row->strategic_plan->name : '';
            });

            /*$table->addColumn('team_name', function ($row) {
                return $row->team ? $row->team->name : '';
            });*/

            $table->rawColumns(['actions', 'placeholder', 'user', 'strategic_plan']);

            return $table->make(true);
        }

        return view('admin.goals.index');
    }

    public function create()
    {
        abort_if(Gate::denies('goal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id');

        $strategic_plans = StrategicPlan::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.goals.create', compact('users', 'strategic_plans'));
    }

    public function store(StoreGoalRequest $request)
    {
        $goal = Goal::create($request->all());
        $goal->users()->sync($request->input('users', []));

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $goal->id]);
        }

        return redirect()->route('admin.goals.index');
    }

    public function edit(Goal $goal)
    {
        abort_if(Gate::denies('goal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id');

        $strategic_plans = StrategicPlan::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$teams = Team::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $goal->load('users', 'strategic_plan');

        return view('admin.goals.edit', compact('users', 'strategic_plans', 'goal'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $goal->update($request->all());
        $goal->users()->sync($request->input('users', []));

        return redirect()->route('admin.goals.index');
    }

    public function show(Goal $goal)
    {
        abort_if(Gate::denies('goal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $goal->load('users', 'strategic_plan', 'goalProjects');

        return view('admin.goals.show', compact('goal'));
    }

    public function destroy(Goal $goal)
    {
        abort_if(Gate::denies('goal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $goal->delete();

        return back();
    }

    public function massDestroy(MassDestroyGoalRequest $request)
    {
        Goal::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('goal_create') && Gate::denies('goal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Goal();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStrategicPlanRequest;
use App\Http\Requests\StoreStrategicPlanRequest;
use App\Http\Requests\UpdateStrategicPlanRequest;
use App\Models\StrategicPlan;
use App\Models\Goal;
use App\Models\Project;
use App\Models\Initiative;
use App\Models\Risk;
use App\Models\ActionPlan;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StrategicPlansController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('strategic_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = StrategicPlan::with(['team'])->select(sprintf('%s.*', (new StrategicPlan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'strategic_plan_show';
                $editGate      = 'strategic_plan_edit';
                $deleteGate    = 'strategic_plan_delete';
                $archiveGate   = 'strategic_plan_archive';
                $crudRoutePart = 'strategic-plans';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'archiveGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.strategicPlans.index');
    }

    public function archiveList(Request $request)
    {
        abort_if(Gate::denies('strategic_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {

            $query = StrategicPlan::select(sprintf('%s.*', (new StrategicPlan)->table))->onlyArchived();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                //$viewGate      = 'strategic_plan_show';
                $restoreGate   = 'strategic_plan_restore';
                $crudRoutePart = 'strategic-plans';

                return view('partials.datatablesActions', compact(
                    //'viewGate',
                    'restoreGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.strategicPlans.archive');
    }

    public function create()
    {
        abort_if(Gate::denies('strategic_plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.strategicPlans.create');
    }

    public function store(StoreStrategicPlanRequest $request)
    {
        $strategicPlan = StrategicPlan::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $strategicPlan->id]);
        }

        return redirect()->route('admin.strategic-plans.index');
    }

    public function edit(StrategicPlan $strategicPlan)
    {
        abort_if(Gate::denies('strategic_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $strategicPlan->load('team');

        return view('admin.strategicPlans.edit', compact('strategicPlan'));
    }

    public function update(UpdateStrategicPlanRequest $request, StrategicPlan $strategicPlan)
    {
        $strategicPlan->update($request->all());

        return redirect()->route('admin.strategic-plans.index');
    }

    public function show(StrategicPlan $strategicPlan)
    {
        abort_if(Gate::denies('strategic_plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $strategicPlan->load('team', 'strategicPlanGoals');

        return view('admin.strategicPlans.show', compact('strategicPlan'));
    }

    public function destroy(StrategicPlan $strategicPlan)
    {
        abort_if(Gate::denies('strategic_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $strategicPlan->delete();

        return back();
    }

    public function massDestroy(MassDestroyStrategicPlanRequest $request)
    {
        StrategicPlan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('strategic_plan_create') && Gate::denies('strategic_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new StrategicPlan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function archive (StrategicPlan $strategicPlan){

      abort_if(Gate::denies('strategic_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
      $goals = $strategicPlan->load('strategicPlanGoals');

      foreach ($goals->strategicPlanGoals as $key => $index){
        $goal_id = $index->id;
        $goal = Goal::findOrFail($goal_id);
        $projects = $goal->load('goalProjects');

        foreach ($projects->goalProjects as $key => $index){
          $project_id = $index->id;
          $project = Project::findOrFail($project_id);
            $initiatives = $project->load('projectInitiatives');

            foreach ($initiatives->projectInitiatives as $key => $index){
              $initiative_id = $index->id;
              $initiative = Initiative::findOrFail($initiative_id);

                $risks = $initiative->load('initiativeRisks');
                $action_plans = $initiative->load('initiativeActionPlans');
                foreach ($risks->initiativeRisks as $key => $index){
                  $risk_id = $index->id;
                  $risk = Risk::findOrFail($risk_id);
                  $risk->archive();
                }

                foreach ($action_plans->initiativeActionPlans as $key => $index){
                  $action_plan_id = $index->id;
                  $action_plan = ActionPlan::findOrFail($action_plan_id);
                  $action_plan->archive();
                }

              $initiative->archive();
            }

          $project->archive();
        }

        $goal->archive();
      }

      $strategicPlan->archive();
      return back();
    }

    public function restore ($strategic_plan_id){

      abort_if(Gate::denies('strategic_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
      $strategicPlan = StrategicPlan::onlyArchived()->find($strategic_plan_id);

      $goals = Goal::onlyArchived()->where('strategic_plan_id','=',$strategic_plan_id)->get();

      foreach ($goals as $key => $index){
        $goal_id = $index->id;
        $goal = Goal::onlyArchived()->find($goal_id);
        $projects = Project::onlyArchived()->where('goal_id','=',$goal_id)->get();

        foreach ($projects as $key => $index){
          $project_id = $index->id;
          $project = Project::onlyArchived()->find($project_id);
          $initiatives = Initiative::onlyArchived()->where('project_id','=',$project_id)->get();

          foreach ($initiatives as $key => $index){
            $initiative_id = $index->id;
            $initiative = Initiative::onlyArchived()->find($initiative_id);

            $risks = Risk::onlyArchived()->where('initiative_id','=',$initiative_id)->get();
            $action_plans = ActionPlan::onlyArchived()->where('initiative_id','=',$initiative_id)->get();
            foreach ($risks as $key => $index){
              $risk_id = $index->id;
              $risk = Risk::onlyArchived()->find($risk_id);
              $risk->unarchive();
            }

            foreach ($action_plans as $key => $index){
              $action_plan_id = $index->id;
              $action_plan = ActionPlan::onlyArchived()->find($action_plan_id);
              $action_plan->unarchive();
            }

            $initiative->unarchive();
          }

          $project->unarchive();
        }

        $goal->unarchive();
      }

      $strategicPlan->unarchive();
      return back();
    }
}

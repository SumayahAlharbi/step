<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRiskRequest;
use App\Http\Requests\StoreRiskRequest;
use App\Http\Requests\UpdateRiskRequest;
use App\Models\Initiative;
use App\Models\Risk;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RisksController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('risk_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Risk::with(['initiative'])->select(sprintf('%s.*', (new Risk)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'risk_show';
                $editGate      = 'risk_edit';
                $deleteGate    = 'risk_delete';
                $crudRoutePart = 'risks';

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
            $table->editColumn('probability', function ($row) {
                return $row->probability ? $row->probability : "";
            });
            $table->editColumn('impact', function ($row) {
                return $row->impact ? $row->impact : "";
            });
            $table->editColumn('gross', function ($row) {
                return $row->gross ? $row->gross : "";
            });
            $table->editColumn('action', function ($row) {
                return $row->action ? Risk::ACTION_SELECT[$row->action] : '';
            });
            $table->addColumn('initiative_title', function ($row) {
                return $row->initiative ? $row->initiative->title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'initiative']);

            return $table->make(true);
        }

        return view('admin.risks.index');
    }

    public function create()
    {
        abort_if(Gate::denies('risk_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiatives = Initiative::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.risks.create', compact('initiatives'));
    }

    public function store(StoreRiskRequest $request)
    {
        $risk = Risk::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $risk->id]);
        }

        return redirect()->route('admin.risks.index');
    }

    public function edit(Risk $risk)
    {
        abort_if(Gate::denies('risk_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $initiatives = Initiative::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $risk->load('initiative');

        return view('admin.risks.edit', compact('initiatives', 'risk'));
    }

    public function update(UpdateRiskRequest $request, Risk $risk)
    {
        $risk->update($request->all());

        return redirect()->route('admin.risks.index');
    }

    public function show(Risk $risk)
    {
        abort_if(Gate::denies('risk_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk->load('initiative');

        return view('admin.risks.show', compact('risk'));
    }

    public function destroy(Risk $risk)
    {
        abort_if(Gate::denies('risk_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk->delete();

        return back();
    }

    public function massDestroy(MassDestroyRiskRequest $request)
    {
        Risk::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('risk_create') && Gate::denies('risk_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Risk();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

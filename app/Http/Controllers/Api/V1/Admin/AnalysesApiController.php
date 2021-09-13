<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnalysiRequest;
use App\Http\Requests\UpdateAnalysiRequest;
use App\Http\Resources\Admin\AnalysiResource;
use App\Models\Analysi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalysesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('analysi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /* $filters = Input::only('code_interne', 'identification'); */
        return new AnalysiResource(Analysi::select("*")->filter()->get());
    }

    public function store(StoreAnalysiRequest $request)
    {
        $analysi = Analysi::create($request->all());

        return (new AnalysiResource($analysi))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Analysi $analysi)
    {
        abort_if(Gate::denies('analysi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnalysiResource($analysi);
    }

    public function update(UpdateAnalysiRequest $request, Analysi $analysi)
    {
        $analysi->update($request->all());

        return (new AnalysiResource($analysi))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Analysi $analysi)
    {
        abort_if(Gate::denies('analysi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $analysi->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

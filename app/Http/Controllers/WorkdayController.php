<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workday;
use App\Http\Resources\Tabel\WorkdayResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class WorkdayController extends Controller
{
    //
    public function index()
    {
        $workdays = Workday::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => WorkdayResource::collection($workdays),
        ], Response::HTTP_OK);
    }
    public function store(Request $request) 
    {
        //if(workday with this date and employee_id exists, update it
        //else create new workday)
        $workday = Workday::where('date', $request->date)->where('employee_id', $request->employee_id)->first();
        if($workday == null){
            $workday = Workday::create($request->all());
        }else{
            $workday->update($request->all());
        }
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function show(Workday $workday)
    {
        return new JsonResponse([
            'message' => 'success',
            'data' => new WorkdayResource($workday),
        ], Response::HTTP_OK);
    }
    public function update(Request $request)
    {
        $workday = Workday::find($request->id);
        $workday->update($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $workday = Workday::find($request->id);
        $workday->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

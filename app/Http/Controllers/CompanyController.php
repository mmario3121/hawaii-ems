<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class CompanyController extends Controller
{
    //
    public function index()
    {
        $companies = Company::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => CompanyResource::collection($companies),
        ], Response::HTTP_OK);
    }

    public function store(StoreCompanyRequest $request)
    {

        $validated = $request->validated();
        $company = Company::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateCompanyRequest $request)
    {
        $validated = $request->validated();
        $company = Company::find($validated['id']);
        $company->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $company = Company::find($request->id);
        $company->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function destroy(Company $company)
    {
        $company->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

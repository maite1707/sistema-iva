<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = auth()->user()
            ->companies()
            ->latest()
            ->paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'nit' => ['nullable', 'string', 'max:30'],
            'nrc' => ['nullable', 'string', 'max:30', 'unique:companies,nrc'],
            'dui' => ['nullable', 'string', 'max:20'],
            'economic_activity' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
        ]);

        $validated['iva_taxpayer'] = $request->boolean('iva_taxpayer');
        $validated['status'] = $request->boolean('status');

        $company = Company::create($validated);

        auth()->user()->companies()->attach(
            $company->id,
            [
                'is_admin' => true,
                'status' => true,
            ]
        );

        session([
            'company_id' => $company->id,
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Company $company)
    {
        $this->authorizeCompany($company);

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeCompany($company);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'nit' => ['nullable', 'string', 'max:30'],
            'nrc' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('companies', 'nrc')->ignore($company->id),
            ],
            'dui' => ['nullable', 'string', 'max:20'],
            'economic_activity' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
        ]);

        $validated['iva_taxpayer'] = $request->boolean('iva_taxpayer');
        $validated['status'] = $request->boolean('status');

        $company->update($validated);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Company $company)
    {
        $this->authorizeCompany($company);

        $company->update([
            'status' => false,
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa desactivada.');
    }

    private function authorizeCompany(Company $company): void
    {
        abort_unless(
            auth()->user()
                ->companies()
                ->where('companies.id', $company->id)
                ->exists(),
            403
        );
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanySelectionController extends Controller
{
    public function index()
    {
        $companies = auth()->user()
            ->companies()
            ->where('companies.status', true)
            ->wherePivot('status', true)
            ->get();

        return view('companies.select', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => ['required', 'integer'],
        ]);

        $company = auth()->user()
            ->companies()
            ->where('companies.id', $request->company_id)
            ->where('companies.status', true)
            ->firstOrFail();

        session([
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Empresa seleccionada correctamente.');
    }

    public function destroy()
    {
        session()->forget([
            'company_id',
            'company_name',
        ]);

        return redirect()->route('companies.select');
    }
}
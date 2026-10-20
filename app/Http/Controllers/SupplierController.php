<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('company_id', session('company_id'))
            ->latest()
            ->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'document_type' => ['required', 'in:NIT,DUI,NRC,PASAPORTE,CARNET_RESIDENTE,OTRO'],
            'document_number' => ['nullable', 'string', 'max:30'],
            'nrc' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['company_id'] = session('company_id');
        $validated['is_iva_withholding_agent'] = $request->boolean('is_iva_withholding_agent');
        $validated['status'] = $request->boolean('status', true);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'document_type' => ['required', 'in:NIT,DUI,NRC,PASAPORTE,CARNET_RESIDENTE,OTRO'],
            'document_number' => ['nullable', 'string', 'max:30'],
            'nrc' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['is_iva_withholding_agent'] = $request->boolean('is_iva_withholding_agent');
        $validated['status'] = $request->boolean('status');

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        $supplier->update([
            'status' => false,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor desactivado.');
    }

    private function authorizeSupplier(Supplier $supplier): void
    {
        abort_unless(
            $supplier->company_id === session('company_id'),
            403
        );
    }
}
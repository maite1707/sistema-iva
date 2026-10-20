<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('company_id', session('company_id'))
            ->latest()
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'document_type' => ['required', 'in:NIT,DUI,NRC,PASAPORTE,CARNET_RESIDENTE,OTRO'],
            'document_number' => ['nullable', 'string', 'max:30'],
            'nrc' => ['nullable', 'string', 'max:30'],
            'taxpayer_type' => ['required', 'in:CONTRIBUYENTE,CONSUMIDOR_FINAL'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['company_id'] = session('company_id');
        $validated['status'] = $request->boolean('status', true);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Customer $customer)
    {
        $this->authorizeCustomer($customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeCustomer($customer);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'document_type' => ['required', 'in:NIT,DUI,NRC,PASAPORTE,CARNET_RESIDENTE,OTRO'],
            'document_number' => ['nullable', 'string', 'max:30'],
            'nrc' => ['nullable', 'string', 'max:30'],
            'taxpayer_type' => ['required', 'in:CONTRIBUYENTE,CONSUMIDOR_FINAL'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['status'] = $request->boolean('status');

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeCustomer($customer);

        $customer->update([
            'status' => false,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente desactivado.');
    }

    private function authorizeCustomer(Customer $customer): void
    {
        abort_unless(
            $customer->company_id === session('company_id'),
            403
        );
    }
}
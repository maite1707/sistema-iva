<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\IvaRate;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::where('company_id', session('company_id'))
            ->with(['customer', 'documentType'])
            ->latest('issue_date')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::where('company_id', session('company_id'))
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $documentTypes = DocumentType::where('status', true)
            ->whereIn('applies_to', ['VENTAS', 'AMBOS'])
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('customers', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'taxable_amount' => ['required', 'numeric', 'min:0'],
            'exempt_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $customer = Customer::where('id', $validated['customer_id'])
            ->where('company_id', session('company_id'))
            ->firstOrFail();

        $ivaRate = IvaRate::where('is_default', true)->first();
        $percentage = $ivaRate ? $ivaRate->percentage / 100 : 0.13;

        $exemptAmount = $validated['exempt_amount'] ?? 0;
        $ivaAmount = round($validated['taxable_amount'] * $percentage, 2);
        $total = $validated['taxable_amount'] + $exemptAmount + $ivaAmount;

        Sale::create([
            'company_id' => session('company_id'),
            'customer_id' => $customer->id,
            'document_type_id' => $validated['document_type_id'],
            'document_number' => $validated['document_number'],
            'issue_date' => $validated['issue_date'],
            'taxpayer_type_snapshot' => $customer->taxpayer_type,
            'taxable_amount' => $validated['taxable_amount'],
            'exempt_amount' => $exemptAmount,
            'iva_amount' => $ivaAmount,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
            'status' => true,
        ]);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function edit(Sale $sale)
    {
        $this->authorizeSale($sale);

        $customers = Customer::where('company_id', session('company_id'))
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $documentTypes = DocumentType::where('status', true)
            ->whereIn('applies_to', ['VENTAS', 'AMBOS'])
            ->orderBy('name')
            ->get();

        return view('sales.edit', compact('sale', 'customers', 'documentTypes'));
    }

    public function update(Request $request, Sale $sale)
    {
        $this->authorizeSale($sale);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'taxable_amount' => ['required', 'numeric', 'min:0'],
            'exempt_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $customer = Customer::where('id', $validated['customer_id'])
            ->where('company_id', session('company_id'))
            ->firstOrFail();

        $ivaRate = IvaRate::where('is_default', true)->first();
        $percentage = $ivaRate ? $ivaRate->percentage / 100 : 0.13;

        $exemptAmount = $validated['exempt_amount'] ?? 0;
        $ivaAmount = round($validated['taxable_amount'] * $percentage, 2);
        $total = $validated['taxable_amount'] + $exemptAmount + $ivaAmount;

        $sale->update([
            'customer_id' => $customer->id,
            'document_type_id' => $validated['document_type_id'],
            'document_number' => $validated['document_number'],
            'issue_date' => $validated['issue_date'],
            'taxpayer_type_snapshot' => $customer->taxpayer_type,
            'taxable_amount' => $validated['taxable_amount'],
            'exempt_amount' => $exemptAmount,
            'iva_amount' => $ivaAmount,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Sale $sale)
    {
        $this->authorizeSale($sale);

        $sale->update([
            'status' => false,
        ]);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venta anulada.');
    }

    private function authorizeSale(Sale $sale): void
    {
        abort_unless(
            $sale->company_id === session('company_id'),
            403
        );
    }
}
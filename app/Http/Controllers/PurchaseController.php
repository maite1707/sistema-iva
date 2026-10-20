<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\DocumentType;
use App\Models\IvaRate;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::where('company_id', session('company_id'))
            ->with(['supplier', 'documentType'])
            ->latest('issue_date')
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('company_id', session('company_id'))
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $documentTypes = DocumentType::where('status', true)
            ->whereIn('applies_to', ['COMPRAS', 'AMBOS'])
            ->orderBy('name')
            ->get();

        return view('purchases.create', compact('suppliers', 'documentTypes'));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'taxable_amount' => ['required', 'numeric', 'min:0'],
            'exempt_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_excluded_subject' => ['nullable', 'boolean'],
            'renta_operation_type' => ['nullable', 'string', 'max:100'],
            'renta_classification' => ['nullable', 'string', 'max:100'],
            'renta_sector' => ['nullable', 'string', 'max:100'],
            'renta_cost_type' => ['nullable', 'string', 'max:100'],
        ]);

        $this->authorizeSupplier($validated['supplier_id']);

        $ivaRate = IvaRate::where('is_default', true)->first();
        $percentage = $ivaRate ? $ivaRate->percentage / 100 : 0.13;

        $isExcludedSubject = $request->boolean('is_excluded_subject');

        $exemptAmount = $validated['exempt_amount'] ?? 0;
        // Un sujeto excluido no cobra IVA, así que no genera crédito fiscal
        $ivaAmount = $isExcludedSubject ? 0 : round($validated['taxable_amount'] * $percentage, 2);
        $total = $validated['taxable_amount'] + $exemptAmount + $ivaAmount;
        $retentionAmount = $isExcludedSubject ? round($validated['taxable_amount'] * 0.13, 2) : null;

        Purchase::create([
            'company_id' => session('company_id'),
            'supplier_id' => $validated['supplier_id'],
            'document_type_id' => $validated['document_type_id'],
            'document_number' => $validated['document_number'],
            'issue_date' => $validated['issue_date'],
            'taxable_amount' => $validated['taxable_amount'],
            'exempt_amount' => $exemptAmount,
            'iva_amount' => $ivaAmount,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
            'status' => true,
            'is_excluded_subject' => $isExcludedSubject,
            'retention_amount' => $retentionAmount,
            'renta_operation_type' => $validated['renta_operation_type'] ?? null,
            'renta_classification' => $validated['renta_classification'] ?? null,
            'renta_sector' => $validated['renta_sector'] ?? null,
            'renta_cost_type' => $validated['renta_cost_type'] ?? null,
        ]);

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Compra registrada correctamente.');
    }

    public function edit(Purchase $purchase)
    {
        $this->authorizePurchase($purchase);

        $suppliers = Supplier::where('company_id', session('company_id'))
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $documentTypes = DocumentType::where('status', true)
            ->whereIn('applies_to', ['COMPRAS', 'AMBOS'])
            ->orderBy('name')
            ->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'documentTypes'));
    }

        public function update(Request $request, Purchase $purchase)
    {
        $this->authorizePurchase($purchase);

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'taxable_amount' => ['required', 'numeric', 'min:0'],
            'exempt_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_excluded_subject' => ['nullable', 'boolean'],
            'renta_operation_type' => ['nullable', 'string', 'max:100'],
            'renta_classification' => ['nullable', 'string', 'max:100'],
            'renta_sector' => ['nullable', 'string', 'max:100'],
            'renta_cost_type' => ['nullable', 'string', 'max:100'],
        ]);

        $this->authorizeSupplier($validated['supplier_id']);

        $ivaRate = IvaRate::where('is_default', true)->first();
        $percentage = $ivaRate ? $ivaRate->percentage / 100 : 0.13;

        $isExcludedSubject = $request->boolean('is_excluded_subject');

        $exemptAmount = $validated['exempt_amount'] ?? 0;
        // Un sujeto excluido no cobra IVA, así que no genera crédito fiscal
        $ivaAmount = $isExcludedSubject ? 0 : round($validated['taxable_amount'] * $percentage, 2);
        $total = $validated['taxable_amount'] + $exemptAmount + $ivaAmount;
        $retentionAmount = $isExcludedSubject ? round($validated['taxable_amount'] * 0.13, 2) : null;

        $purchase->update([
            'supplier_id' => $validated['supplier_id'],
            'document_type_id' => $validated['document_type_id'],
            'document_number' => $validated['document_number'],
            'issue_date' => $validated['issue_date'],
            'taxable_amount' => $validated['taxable_amount'],
            'exempt_amount' => $exemptAmount,
            'iva_amount' => $ivaAmount,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
            'is_excluded_subject' => $isExcludedSubject,
            'retention_amount' => $retentionAmount,
            'renta_operation_type' => $validated['renta_operation_type'] ?? null,
            'renta_classification' => $validated['renta_classification'] ?? null,
            'renta_sector' => $validated['renta_sector'] ?? null,
            'renta_cost_type' => $validated['renta_cost_type'] ?? null,
        ]);

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Compra actualizada correctamente.');
    }

    public function destroy(Purchase $purchase)
    {
        $this->authorizePurchase($purchase);

        $purchase->update([
            'status' => false,
        ]);

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Compra anulada.');
    }

    private function authorizePurchase(Purchase $purchase): void
    {
        abort_unless(
            $purchase->company_id === session('company_id'),
            403
        );
    }

    private function authorizeSupplier(int $supplierId): void
    {
        abort_unless(
            Supplier::where('id', $supplierId)
                ->where('company_id', session('company_id'))
                ->exists(),
            403
        );
    }
}
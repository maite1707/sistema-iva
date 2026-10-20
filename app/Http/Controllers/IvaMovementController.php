<?php

namespace App\Http\Controllers;

use App\Models\IvaMovement;
use Illuminate\Http\Request;

class IvaMovementController extends Controller
{
    private const COMBINATIONS = [
        'A6' => ['movement_type' => 'ANTICIPO', 'direction' => 'RECIBIDO', 'percentage' => 2.00],
        'A7' => ['movement_type' => 'RETENCION', 'direction' => 'RECIBIDO', 'percentage' => 1.00],
        'A8' => ['movement_type' => 'PERCEPCION', 'direction' => 'RECIBIDO', 'percentage' => 1.00],
        'A9' => ['movement_type' => 'PERCEPCION', 'direction' => 'EMITIDO', 'percentage' => 1.00],
        'A10' => ['movement_type' => 'RETENCION', 'direction' => 'EMITIDO', 'percentage' => 1.00],
        'A11' => ['movement_type' => 'ANTICIPO', 'direction' => 'EMITIDO', 'percentage' => 2.00],
        'A12' => ['movement_type' => 'RETENCION', 'direction' => 'EMITIDO', 'percentage' => 13.00],
    ];

    public function index()
    {
        $movements = IvaMovement::where('company_id', session('company_id'))
            ->where('status', true)
            ->latest('issue_date')
            ->paginate(15);

        return view('iva-movements.index', compact('movements'));
    }

    public function create()
    {
        return view('iva-movements.create', [
            'annexOptions' => self::COMBINATIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annex_code' => ['required', 'in:A6,A7,A8,A9,A10,A11,A12'],
            'counterparty_nit' => ['nullable', 'string', 'max:30'],
            'counterparty_dui' => ['nullable', 'string', 'max:20'],
            'counterparty_name' => ['nullable', 'string', 'max:200'],
            'document_type' => ['nullable', 'string', 'max:50'],
            'document_resolution' => ['nullable', 'string', 'max:50'],
            'document_series' => ['nullable', 'string', 'max:50'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'taxable_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $combination = self::COMBINATIONS[$validated['annex_code']];

        $taxAmount = round($validated['taxable_amount'] * ($combination['percentage'] / 100), 2);

        IvaMovement::create([
            'company_id' => session('company_id'),
            'movement_type' => $combination['movement_type'],
            'direction' => $combination['direction'],
            'percentage' => $combination['percentage'],
            'counterparty_nit' => $validated['counterparty_nit'] ?? null,
            'counterparty_dui' => $validated['counterparty_dui'] ?? null,
            'counterparty_name' => $validated['counterparty_name'] ?? null,
            'document_type' => $validated['document_type'] ?? null,
            'document_resolution' => $validated['document_resolution'] ?? null,
            'document_series' => $validated['document_series'] ?? null,
            'document_number' => $validated['document_number'] ?? null,
            'issue_date' => $validated['issue_date'],
            'taxable_amount' => $validated['taxable_amount'],
            'tax_amount' => $taxAmount,
            'status' => true,
        ]);

        return redirect()
            ->route('iva-movements.index')
            ->with('success', 'Movimiento de IVA registrado correctamente.');
    }

    public function destroy(IvaMovement $ivaMovement)
    {
        abort_unless($ivaMovement->company_id === session('company_id'), 403);

        $ivaMovement->update(['status' => false]);

        return redirect()
            ->route('iva-movements.index')
            ->with('success', 'Movimiento anulado.');
    }
}
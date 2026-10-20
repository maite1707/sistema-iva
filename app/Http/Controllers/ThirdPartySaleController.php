<?php

namespace App\Http\Controllers;

use App\Models\IvaRate;
use App\Models\ThirdPartySale;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThirdPartySaleController extends Controller
{
    public function index(): View
    {
        $thirdPartySales = ThirdPartySale::where('company_id', session('company_id'))
            ->where('status', true)
            ->orderBy('document_date', 'desc')
            ->get();

        return view('third-party-sales.index', compact('thirdPartySales'));
    }

    public function create(): View
    {
        return view('third-party-sales.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mandante_nit_nrc' => ['nullable', 'string', 'max:30'],
            'mandante_name' => ['required', 'string', 'max:200'],
            'mandante_dui' => ['nullable', 'string', 'max:20'],
            'document_type' => ['nullable', 'string', 'max:50'],
            'document_series' => ['nullable', 'string', 'max:50'],
            'document_resolution' => ['nullable', 'string', 'max:50'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'document_date' => ['required', 'date'],
            'sale_amount' => ['required', 'numeric', 'min:0'],
            'settlement_series' => ['nullable', 'string', 'max:50'],
            'settlement_resolution' => ['nullable', 'string', 'max:50'],
            'settlement_number' => ['nullable', 'string', 'max:50'],
            'settlement_date' => ['nullable', 'date'],
        ]);

        $ivaRate = IvaRate::where('is_default', true)->first();
        $percentage = $ivaRate ? $ivaRate->percentage / 100 : 0.13;
        $taxAmount = round($validated['sale_amount'] * $percentage, 2);

        ThirdPartySale::create([
            ...$validated,
            'company_id' => session('company_id'),
            'tax_amount' => $taxAmount,
        ]);

        return redirect()->route('third-party-sales.index')
            ->with('success', 'Venta a cuenta de terceros registrada correctamente.');
    }

    public function destroy(ThirdPartySale $thirdPartySale): RedirectResponse
    {
        if ($thirdPartySale->company_id !== session('company_id')) {
            abort(403);
        }

        $thirdPartySale->update(['status' => false]);

        return redirect()->route('third-party-sales.index')
            ->with('success', 'Registro anulado correctamente.');
    }
}
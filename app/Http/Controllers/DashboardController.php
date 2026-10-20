<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('company_id');
        $company = Company::findOrFail($companyId);

        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $sales = Sale::where('company_id', $companyId)
            ->where('status', true)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->get(['taxable_amount', 'exempt_amount', 'iva_amount']);

        $purchases = Purchase::where('company_id', $companyId)
            ->where('status', true)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->get(['taxable_amount', 'exempt_amount', 'iva_amount', 'is_excluded_subject']);

        $salesTotal = round($sales->sum(fn ($s) => $s->taxable_amount + $s->exempt_amount), 2);
        $ivaDebit = round($sales->sum('iva_amount'), 2);

        $purchasesTotal = round($purchases->sum(fn ($p) => $p->taxable_amount + $p->exempt_amount), 2);
        // Las compras a sujetos excluidos no generan crédito fiscal
        $ivaCredit = round($purchases->where('is_excluded_subject', false)->sum('iva_amount'), 2);

        $ivaToPay = round($ivaDebit - $ivaCredit, 2);

        return view('dashboard', [
            'company' => $company,
            'month' => $month,
            'year' => $year,
            'salesTotal' => $salesTotal,
            'salesCount' => $sales->count(),
            'purchasesTotal' => $purchasesTotal,
            'purchasesCount' => $purchases->count(),
            'ivaDebit' => $ivaDebit,
            'ivaCredit' => $ivaCredit,
            'ivaToPay' => $ivaToPay,
        ]);
    }

    // Permite que la ruta funcione sin importar si apunta a "index" o al controlador directo
    public function __invoke(Request $request)
    {
        return $this->index($request);
    }
}
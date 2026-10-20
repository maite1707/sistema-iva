<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleBookController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $sales = Sale::where('company_id', session('company_id'))
            ->where('status', true)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['customer', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        $totals = [
            'taxable_amount' => $sales->sum('taxable_amount'),
            'exempt_amount' => $sales->sum('exempt_amount'),
            'iva_amount' => $sales->sum('iva_amount'),
            'total' => $sales->sum('total'),
        ];

        return view('sales.book', compact('sales', 'totals', 'month', 'year'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseBookController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $purchases = Purchase::where('company_id', session('company_id'))
            ->where('status', true)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['supplier', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        $totals = [
            'taxable_amount' => $purchases->sum('taxable_amount'),
            'exempt_amount' => $purchases->sum('exempt_amount'),
            'iva_amount' => $purchases->sum('iva_amount'),
            'total' => $purchases->sum('total'),
        ];

        return view('purchases.book', compact('purchases', 'totals', 'month', 'year'));
    }
}
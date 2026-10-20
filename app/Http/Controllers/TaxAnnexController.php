<?php

namespace App\Http\Controllers;

use App\Models\TaxAnnex;
use App\Models\TaxAnnexGeneration;
use App\Services\TaxAnnexGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaxAnnexController extends Controller
{
    public function index()
    {
        $annexes = TaxAnnex::where('active', true)
            ->orderBy('code')
            ->get();

        $generations = TaxAnnexGeneration::where('company_id', session('company_id'))
            ->with('taxAnnex')
            ->latest()
            ->limit(10)
            ->get();

        return view('tax-annexes.index', compact('annexes', 'generations'));
    }

    public function generate(Request $request, TaxAnnexGeneratorService $generator)
    {
        $validated = $request->validate([
            'annex_code' => ['required', 'in:A1,A2,A3,A4,A5,A6,A7,A8,A9,A10,A11,A12'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
        ]);

        $result = $generator->generate(
            $validated['annex_code'],
            session('company_id'),
            $validated['month'],
            $validated['year'],
            auth()->id()
        );

        if ($result['total_records'] === 0) {
            return redirect()
                ->route('tax-annexes.index')
                ->with('warning', 'No se encontraron registros para ese período. No se generó ningún archivo.');
        }

        return redirect()
            ->route('tax-annexes.index')
            ->with('success', "Anexo generado correctamente con {$result['total_records']} registros.");
    }

    public function download(TaxAnnexGeneration $generation)
    {
        abort_unless($generation->company_id === session('company_id'), 403);

        abort_unless(Storage::exists($generation->file_path), 404);

        return Storage::download($generation->file_path);
    }
}
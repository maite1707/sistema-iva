<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('company_id')) {
            return redirect()
                ->route('companies.select')
                ->with('warning', 'Debe seleccionar una empresa.');
        }

        return $next($request);
    }
}
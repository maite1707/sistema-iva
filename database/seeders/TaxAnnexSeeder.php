<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxAnnexSeeder extends Seeder
{
    public function run(): void
    {
        $annexes = [
            ['code' => 'A1', 'name' => 'Detalle de Ventas a Contribuyentes', 'f07_box' => null],
            ['code' => 'A2', 'name' => 'Detalle de Ventas a Consumidor Final', 'f07_box' => null],
            ['code' => 'A3', 'name' => 'Detalle de Compras', 'f07_box' => null],
            ['code' => 'A4', 'name' => 'Detalle de Ventas a Cuenta de Terceros', 'f07_box' => 108],
            ['code' => 'A5', 'name' => 'Detalle de Compras a Sujetos Excluidos', 'f07_box' => 66],
            ['code' => 'A6', 'name' => 'Anticipo a Cuenta del 2% IVA efectuada al Declarante', 'f07_box' => 161],
            ['code' => 'A7', 'name' => 'Retención del 1% IVA efectuada al Declarante', 'f07_box' => 162],
            ['code' => 'A8', 'name' => 'Percepción del 1% IVA efectuada al Declarante', 'f07_box' => 163],
            ['code' => 'A9', 'name' => 'Percepción del 1% IVA efectuada por el Declarante', 'f07_box' => 169],
            ['code' => 'A10', 'name' => 'Retención del 1% IVA efectuada por el Declarante', 'f07_box' => 170],
            ['code' => 'A11', 'name' => 'Anticipo a Cuenta del 2% IVA efectuada por el Declarante', 'f07_box' => null],
            ['code' => 'A12', 'name' => 'Retención del 13% IVA efectuada por el Declarante', 'f07_box' => null],
        ];

        foreach ($annexes as $annex) {
            DB::table('tax_annexes')->updateOrInsert(
                ['code' => $annex['code']],
                [
                    'name' => $annex['name'],
                    'f07_box' => $annex['f07_box'],
                    'version' => '2021',
                    'tax_form' => 'F07',
                    'active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => '01', 'name' => 'Factura', 'applies_to' => 'VENTAS'],
            ['code' => '03', 'name' => 'Comprobante de Crédito Fiscal', 'applies_to' => 'AMBOS'],
            ['code' => '05', 'name' => 'Nota de Crédito', 'applies_to' => 'AMBOS'],
            ['code' => '06', 'name' => 'Nota de Débito', 'applies_to' => 'AMBOS'],
            ['code' => '11', 'name' => 'Factura de Exportación', 'applies_to' => 'VENTAS'],
            ['code' => '14', 'name' => 'Factura de Sujeto Excluido', 'applies_to' => 'COMPRAS'],
        ];

        foreach ($types as $type) {
            DocumentType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
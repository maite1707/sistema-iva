<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\TaxAnnex;
use App\Models\TaxAnnexField;
use App\Models\TaxAnnexGeneration;
use App\Models\IvaMovement;
use App\Models\ThirdPartySale;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!function_exists('array_intersect_key_order')) {
    function array_intersect_key_order(array $array, array $orderedKeys): array
    {
        $result = [];
        foreach ($orderedKeys as $key) {
            $result[$key] = $array[$key] ?? '';
        }
        return $result;
    }
}

class TaxAnnexGeneratorService

{
    public function generate(string $annexCode, int $companyId, int $month, int $year, int $userId): array
    {
        $annex = TaxAnnex::where('code', $annexCode)->firstOrFail();

        $fields = TaxAnnexField::where('tax_annex_id', $annex->id)
            ->where('active', true)
            ->orderBy('field_order')
            ->get();

        $rows = match ($annexCode) {
            'A1' => $this->buildA1($companyId, $month, $year),
            'A2' => $this->buildA2($companyId, $month, $year),
            'A3' => $this->buildA3($companyId, $month, $year),
            'A4' => $this->buildA4($companyId, $month, $year),
            'A5' => $this->buildA5($companyId, $month, $year),
            'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12' => $this->buildIvaMovementAnnex($annexCode, $companyId, $month, $year),
            default => throw new \InvalidArgumentException("Anexo {$annexCode} no soportado aún."),
        };

        $csvPath = $this->exportToXlsx($annex, $fields, $rows, $companyId, $month, $year);

        $generation = TaxAnnexGeneration::create([
            'company_id' => $companyId,
            'tax_annex_id' => $annex->id,
            'period_month' => $month,
            'period_year' => $year,
            'total_records' => count($rows),
            'file_path' => $csvPath,
            'generated_by' => $userId,
        ]);

        return [
            'generation' => $generation,
            'file_path' => $csvPath,
            'total_records' => count($rows),
        ];
    }

    private function buildA1(int $companyId, int $month, int $year): array
    {
        $sales = Sale::where('company_id', $companyId)
            ->where('status', true)
            ->where('taxpayer_type_snapshot', 'CONTRIBUYENTE')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['customer', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        return $sales->map(function (Sale $sale) {
            return [
                'fecha_emision' => $sale->issue_date->format('d/m/Y'),
                'clase_documento' => 'IMPRESO',
                'tipo_documento_emitido' => $sale->documentType->code,
                'numero_resolucion' => '',
                'serie_documento' => '',
                'numero_correlativo' => $sale->document_number,
                'numero_control_interno' => $sale->document_number,
                'nit_nrc_cliente' => $sale->customer->nrc ?? $sale->customer->document_number ?? '',
                'nombre_cliente' => $sale->customer->name,
                'ventas_exentas' => number_format((float) $sale->exempt_amount, 2, '.', ''),
                'ventas_no_sujetas' => '0.00',
                'ventas_gravadas_locales' => number_format((float) $sale->taxable_amount, 2, '.', ''),
                'debito_fiscal_ventas_gravadas' => number_format((float) $sale->iva_amount, 2, '.', ''),
                'ventas_terceros_no_domiciliados' => '0.00',
                'debito_fiscal_terceros_no_domiciliados' => '0.00',
                'total_ventas' => number_format((float) $sale->total, 2, '.', ''),
                'dui_cliente' => $sale->customer->document_type === 'DUI' ? $sale->customer->document_number : '',
                'tipo_operacion_renta' => '',
                'tipo_ingreso_renta' => '',
                'numero_anexo' => 1,
            ];
        })->toArray();
    }

    private function buildA2(int $companyId, int $month, int $year): array
    {
        $sales = Sale::where('company_id', $companyId)
            ->where('status', true)
            ->where('taxpayer_type_snapshot', 'CONSUMIDOR_FINAL')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['customer', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        return $sales->map(function (Sale $sale) {
            return [
                'fecha' => $sale->issue_date->format('d/m/Y'),
                'clase_documento' => 'IMPRESO',
                'tipo_documento_emitido' => $sale->documentType->code,
                'numero_resolucion' => '',
                'serie_documento' => '',
                'numero_control_interno_del' => $sale->document_number,
                'numero_control_interno_al' => $sale->document_number,
                'numero_documento_del' => $sale->document_number,
                'numero_documento_al' => $sale->document_number,
                'numero_maquina' => '',
                'ventas_exentas' => number_format((float) $sale->exempt_amount, 2, '.', ''),
                'ventas_internas_exentas_no_sujetas' => '0.00',
                'ventas_no_sujetas' => '0.00',
                'ventas_gravadas_locales' => number_format((float) $sale->taxable_amount, 2, '.', ''),
                'exportaciones_ca' => '0.00',
                'exportaciones_fuera_ca' => '0.00',
                'exportaciones_servicios' => '0.00',
                'ventas_zonas_francas_dpa' => '0.00',
                'ventas_terceros_no_domiciliados' => '0.00',
                'total_ventas' => number_format((float) $sale->total, 2, '.', ''),
                'tipo_operacion_renta' => '',
                'tipo_ingreso_renta' => '',
                'numero_anexo' => 2,
            ];
        })->toArray();
    }

    private function buildA3(int $companyId, int $month, int $year): array
    {
        $purchases = Purchase::where('company_id', $companyId)
            ->where('status', true)
            ->where('is_excluded_subject', false)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['supplier', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        return $purchases->map(function (Purchase $purchase) {
            return [
                'fecha_emision' => $purchase->issue_date->format('d/m/Y'),
                'clase_documento' => 'IMPRESO',
                'tipo_documento_emitido' => $purchase->documentType->code,
                'numero_correlativo_documento' => $purchase->document_number,
                'nit_nrc_proveedor' => $purchase->supplier->nrc ?? $purchase->supplier->document_number ?? '',
                'nombre_proveedor' => $purchase->supplier->name,
                'compras_internas_exentas' => number_format((float) $purchase->exempt_amount, 2, '.', ''),
                'internaciones_exentas' => '0.00',
                'importaciones_exentas_no_sujetas' => '0.00',
                'compras_internas_gravadas' => number_format((float) $purchase->taxable_amount, 2, '.', ''),
                'internaciones_gravadas_bienes' => '0.00',
                'importaciones_gravadas_bienes' => '0.00',
                'importaciones_servicios_gravados' => '0.00',
                'credito_fiscal' => number_format((float) $purchase->iva_amount, 2, '.', ''),
                'total_compras' => number_format((float) $purchase->total, 2, '.', ''),
                'dui_proveedor' => $purchase->supplier->document_type === 'DUI' ? $purchase->supplier->document_number : '',
                'tipo_operacion_renta' => '',
                'clasificacion_renta' => '',
                'sector_renta' => '',
                'tipo_costo_gasto_renta' => '',
                'numero_anexo' => 3,
            ];
        })->toArray();
    }

        private function buildA4(int $companyId, int $month, int $year): array
    {
        $thirdPartySales = ThirdPartySale::where('company_id', $companyId)
            ->where('status', true)
            ->whereYear('document_date', $year)
            ->whereMonth('document_date', $month)
            ->orderBy('document_date')
            ->get();

        return $thirdPartySales->map(function (ThirdPartySale $sale) {
            return [
                'nit_nrc_mandante' => $sale->mandante_nit_nrc ?? '',
                'nombre_mandante' => $sale->mandante_name,
                'fecha_emision_ccf_factura' => $sale->document_date->format('d/m/Y'),
                'tipo_documento' => $sale->document_type ?? '',
                'serie_ccf_factura' => $sale->document_series ?? '',
                'resolucion_ccf_factura' => $sale->document_resolution ?? '',
                'numero_ccf_factura' => $sale->document_number ?? '',
                'monto_venta' => number_format((float) $sale->sale_amount, 2, '.', ''),
                'debito_fiscal' => number_format((float) $sale->tax_amount, 2, '.', ''),
                'serie_comprobante_liquidacion' => $sale->settlement_series ?? '',
                'resolucion_comprobante_liquidacion' => $sale->settlement_resolution ?? '',
                'numero_comprobante_liquidacion' => $sale->settlement_number ?? '',
                'fecha_comprobante_liquidacion' => $sale->settlement_date?->format('d/m/Y') ?? '',
                'dui_mandante' => $sale->mandante_dui ?? '',
                'numero_anexo' => 4,
            ];
        })->toArray();
    }

    private function buildA5(int $companyId, int $month, int $year): array
    {
        $purchases = Purchase::where('company_id', $companyId)
            ->where('status', true)
            ->where('is_excluded_subject', true)
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with(['supplier', 'documentType'])
            ->orderBy('issue_date')
            ->get();

        return $purchases->map(function (Purchase $purchase) {
            return [
                'tipo_documento' => $purchase->documentType->code ?? '',
                'numero_identificacion_proveedor' => $purchase->supplier->nrc ?? $purchase->supplier->document_number ?? '',
                'nombre_proveedor' => $purchase->supplier->name,
                'fecha_emision' => $purchase->issue_date->format('d/m/Y'),
                'serie' => '',
                'numero_documento_emitido' => $purchase->document_number,
                'monto_compra' => number_format((float) $purchase->taxable_amount, 2, '.', ''),
                'retencion_iva_13' => number_format((float) $purchase->retention_amount, 2, '.', ''),
                'tipo_operacion_renta' => $purchase->renta_operation_type ?? '',
                'clasificacion_renta' => $purchase->renta_classification ?? '',
                'sector_renta' => $purchase->renta_sector ?? '',
                'tipo_costo_gasto_renta' => $purchase->renta_cost_type ?? '',
                'numero_anexo' => 5,
            ];
        })->toArray();
    }

        private function buildIvaMovementAnnex(string $annexCode, int $companyId, int $month, int $year): array
    {
        $combinations = [
            'A6' => ['movement_type' => 'ANTICIPO', 'direction' => 'RECIBIDO'],
            'A7' => ['movement_type' => 'RETENCION', 'direction' => 'RECIBIDO'],
            'A8' => ['movement_type' => 'PERCEPCION', 'direction' => 'RECIBIDO'],
            'A9' => ['movement_type' => 'PERCEPCION', 'direction' => 'EMITIDO'],
            'A10' => ['movement_type' => 'RETENCION', 'direction' => 'EMITIDO', 'percentage' => 1.00],
            'A11' => ['movement_type' => 'ANTICIPO', 'direction' => 'EMITIDO'],
            'A12' => ['movement_type' => 'RETENCION', 'direction' => 'EMITIDO', 'percentage' => 13.00],
        ];

        $combination = $combinations[$annexCode];

        $query = IvaMovement::where('company_id', $companyId)
            ->where('status', true)
            ->where('movement_type', $combination['movement_type'])
            ->where('direction', $combination['direction'])
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month);

        if (isset($combination['percentage'])) {
            $query->where('percentage', $combination['percentage']);
        }

        $movements = $query->orderBy('issue_date')->get();

        $annexNumber = (int) str_replace('A', '', $annexCode);

        return $movements->map(function (IvaMovement $movement) use ($annexCode, $annexNumber) {
            $base = [
                'nit_agente' => $movement->counterparty_nit ?? '',
                'nit_sujeto' => $movement->counterparty_nit ?? '',
                'fecha_emision' => $movement->issue_date->format('d/m/Y'),
                'tipo_documento' => $movement->document_type ?? '',
                'resolucion' => $movement->document_resolution ?? '',
                'serie' => $movement->document_series ?? '',
                'numero_documento' => $movement->document_number ?? '',
                'numero_documento_control' => $movement->document_number ?? '',
                'monto_sujeto' => number_format((float) $movement->taxable_amount, 2, '.', ''),
                'monto_anticipo_2' => number_format((float) $movement->tax_amount, 2, '.', ''),
                'monto_retencion_1' => number_format((float) $movement->tax_amount, 2, '.', ''),
                'monto_percepcion_1' => number_format((float) $movement->tax_amount, 2, '.', ''),
                'monto_retencion_13' => number_format((float) $movement->tax_amount, 2, '.', ''),
                'dui_agente' => $movement->counterparty_dui ?? '',
                'dui_sujeto' => $movement->counterparty_dui ?? '',
                'numero_anexo' => $annexNumber,
            ];

            return match ($annexCode) {
                'A6' => array_intersect_key_order($base, ['nit_agente', 'fecha_emision', 'serie', 'numero_documento', 'monto_sujeto', 'monto_anticipo_2', 'dui_agente', 'numero_anexo']),
                'A7' => array_intersect_key_order($base, ['nit_agente', 'fecha_emision', 'tipo_documento', 'serie', 'numero_documento', 'monto_sujeto', 'monto_retencion_1', 'dui_agente', 'numero_anexo']),
                'A8' => array_intersect_key_order($base, ['nit_agente', 'fecha_emision', 'tipo_documento', 'serie', 'numero_documento', 'monto_sujeto', 'monto_percepcion_1', 'dui_agente', 'numero_anexo']),
                'A9' => array_intersect_key_order($base, ['nit_sujeto', 'fecha_emision', 'tipo_documento', 'resolucion', 'serie', 'numero_documento_control', 'monto_sujeto', 'monto_percepcion_1', 'dui_sujeto', 'numero_anexo']),
                'A10' => array_intersect_key_order($base, ['nit_sujeto', 'fecha_emision', 'tipo_documento', 'resolucion', 'serie', 'numero_documento', 'monto_sujeto', 'monto_retencion_1', 'dui_sujeto', 'numero_anexo']),
                'A11' => array_intersect_key_order($base, ['nit_sujeto', 'fecha_emision', 'resolucion', 'serie', 'numero_documento', 'monto_sujeto', 'monto_anticipo_2', 'dui_sujeto', 'numero_anexo']),
                'A12' => array_intersect_key_order($base, ['nit_sujeto', 'fecha_emision', 'tipo_documento', 'serie', 'resolucion', 'numero_documento', 'monto_sujeto', 'monto_retencion_13', 'dui_sujeto', 'numero_anexo']),
            };
        })->toArray();
    }

           private function exportToXlsx(TaxAnnex $annex, $fields, array $rows, int $companyId, int $month, int $year): string
    {
        $fileName = sprintf(
            '%s_empresa%d_%04d-%02d_%s.xlsx',
            $annex->code,
            $companyId,
            $year,
            $month,
            now()->format('YmdHis')
        );

        $relativePath = 'tax_annexes/' . $fileName;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($annex->code);

        $fields = $fields->values();
        $dataTypes = $fields->pluck('data_type')->toArray();

        // Encabezados (fila 1)
        foreach ($fields as $index => $field) {
            $sheet->setCellValueExplicit([$index + 1, 1], $field->field_name, DataType::TYPE_STRING);
        }

        // Datos (desde la fila 2)
        $rowNumber = 2;
        foreach ($rows as $row) {
            foreach (array_values($row) as $index => $value) {
                $type = $dataTypes[$index] ?? 'TEXT';

                if (in_array($type, ['DECIMAL', 'INTEGER'], true) && $value !== '' && is_numeric($value)) {
                    $sheet->setCellValueExplicit([$index + 1, $rowNumber], (float) $value, DataType::TYPE_NUMERIC);
                } else {
                    $sheet->setCellValueExplicit([$index + 1, $rowNumber], (string) $value, DataType::TYPE_STRING);
                }
            }
            $rowNumber++;
        }

        // Formato: encabezados en negrita y columnas con ancho automático
        $lastColumn = Coordinate::stringFromColumnIndex(max(1, $fields->count()));
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);

        for ($i = 1; $i <= $fields->count(); $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        // Guardar en un archivo temporal y pasarlo al disco de Laravel
        $tempPath = tempnam(sys_get_temp_dir(), 'xlsx_');
        (new Xlsx($spreadsheet))->save($tempPath);
        $content = file_get_contents($tempPath);
        unlink($tempPath);

        \Illuminate\Support\Facades\Storage::disk('local')->put($relativePath, $content);

        return $relativePath;
    }
}
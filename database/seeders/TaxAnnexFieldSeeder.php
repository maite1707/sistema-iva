<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxAnnexFieldSeeder extends Seeder
{
    public function run(): void
    {
        $annexes = DB::table('tax_annexes')->pluck('id', 'code');

        $fields = [];

        $fields['A1'] = [
            ['fecha_emision', 'Fecha Emisión', 'DATE'],
            ['clase_documento', 'Clase de Documento', 'TEXT'],
            ['tipo_documento_emitido', 'Tipo de Documento Emitido', 'TEXT'],
            ['numero_resolucion', 'Número de Resolución', 'TEXT'],
            ['serie_documento', 'Serie de Documento', 'TEXT'],
            ['numero_correlativo', 'Número Correlativo de Documento', 'TEXT'],
            ['numero_control_interno', 'Número de Control Interno', 'TEXT'],
            ['nit_nrc_cliente', 'NIT o NRC del Cliente', 'TEXT'],
            ['nombre_cliente', 'Nombre del Cliente', 'TEXT'],
            ['ventas_exentas', 'Ventas exentas', 'DECIMAL'],
            ['ventas_no_sujetas', 'Ventas no sujetas', 'DECIMAL'],
            ['ventas_gravadas_locales', 'Ventas gravadas locales', 'DECIMAL'],
            ['debito_fiscal_ventas_gravadas', 'Débito Fiscal por ventas gravadas locales', 'DECIMAL'],
            ['ventas_terceros_no_domiciliados', 'Ventas a cuenta de terceros no domiciliados', 'DECIMAL'],
            ['debito_fiscal_terceros_no_domiciliados', 'Débito Fiscal por ventas a cuenta de terceros no domiciliados', 'DECIMAL'],
            ['total_ventas', 'Total Ventas', 'DECIMAL'],
            ['dui_cliente', 'DUI del Cliente', 'DUI'],
            ['tipo_operacion_renta', 'Tipo de Operación (Renta)', 'TEXT'],
            ['tipo_ingreso_renta', 'Tipo de Ingreso (Renta)', 'TEXT'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A2'] = [
            ['fecha', 'Fecha', 'DATE'],
            ['clase_documento', 'Clase de Documento', 'TEXT'],
            ['tipo_documento_emitido', 'Tipo de Documento Emitido', 'TEXT'],
            ['numero_resolucion', 'Número de Resolución', 'TEXT'],
            ['serie_documento', 'Serie de Documento', 'TEXT'],
            ['numero_control_interno_del', 'Número de Control Interno (del)', 'TEXT'],
            ['numero_control_interno_al', 'Número de Control Interno (al)', 'TEXT'],
            ['numero_documento_del', 'Número de documento (del)', 'TEXT'],
            ['numero_documento_al', 'Número de documento (al)', 'TEXT'],
            ['numero_maquina', 'N° de Máquina registradora', 'TEXT'],
            ['ventas_exentas', 'Ventas Exentas', 'DECIMAL'],
            ['ventas_internas_exentas_no_sujetas', 'Ventas Internas Exentas No Sujetas a Proporcionalidad', 'DECIMAL'],
            ['ventas_no_sujetas', 'Ventas No Sujetas', 'DECIMAL'],
            ['ventas_gravadas_locales', 'Ventas gravadas locales', 'DECIMAL'],
            ['exportaciones_ca', 'Exportaciones dentro del área Centroamericana', 'DECIMAL'],
            ['exportaciones_fuera_ca', 'Exportaciones fuera del área Centroamericana', 'DECIMAL'],
            ['exportaciones_servicios', 'Exportaciones de servicios', 'DECIMAL'],
            ['ventas_zonas_francas_dpa', 'Ventas a Zonas Francas y DPA (Tasa cero)', 'DECIMAL'],
            ['ventas_terceros_no_domiciliados', 'Ventas a Cuenta de Terceros No Domiciliados', 'DECIMAL'],
            ['total_ventas', 'Total Ventas', 'DECIMAL'],
            ['tipo_operacion_renta', 'Tipo de Operación (Renta)', 'TEXT'],
            ['tipo_ingreso_renta', 'Tipo de Ingreso (Renta)', 'TEXT'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A3'] = [
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['clase_documento', 'Clase de documento', 'TEXT'],
            ['tipo_documento_emitido', 'Tipo de documento emitido', 'TEXT'],
            ['numero_correlativo_documento', 'Número Correlativo de Documento', 'TEXT'],
            ['nit_nrc_proveedor', 'NIT o NRC del proveedor', 'TEXT'],
            ['nombre_proveedor', 'Nombre del proveedor', 'TEXT'],
            ['compras_internas_exentas', 'Compras Internas Exentas', 'DECIMAL'],
            ['internaciones_exentas', 'Internaciones exentas', 'DECIMAL'],
            ['importaciones_exentas_no_sujetas', 'Importaciones Exentas y/o no sujetas', 'DECIMAL'],
            ['compras_internas_gravadas', 'Compras Internas Gravadas', 'DECIMAL'],
            ['internaciones_gravadas_bienes', 'Internaciones Gravadas de bienes', 'DECIMAL'],
            ['importaciones_gravadas_bienes', 'Importaciones gravadas de bienes', 'DECIMAL'],
            ['importaciones_servicios_gravados', 'Importaciones de servicios gravados', 'DECIMAL'],
            ['credito_fiscal', 'Crédito Fiscal', 'DECIMAL'],
            ['total_compras', 'Total Compras', 'DECIMAL'],
            ['dui_proveedor', 'DUI del Proveedor', 'DUI'],
            ['tipo_operacion_renta', 'Tipo de Operación (Renta)', 'TEXT'],
            ['clasificacion_renta', 'Clasificación (Renta)', 'TEXT'],
            ['sector_renta', 'Sector (Renta)', 'TEXT'],
            ['tipo_costo_gasto_renta', 'Tipo de Costo / Gasto (Renta)', 'TEXT'],
            ['numero_anexo', 'Número de Anexo', 'INTEGER'],
        ];

                $fields['A4'] = [
            ['nit_nrc_mandante', 'NIT o NRC del Mandante', 'TEXT'],
            ['nombre_mandante', 'Nombre Mandante', 'TEXT'],
            ['fecha_emision_ccf_factura', 'Fecha de emisión CCF o Factura', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['serie_ccf_factura', 'Serie de CCF o Factura', 'TEXT'],
            ['resolucion_ccf_factura', 'Resolución de CCF o Factura', 'TEXT'],
            ['numero_ccf_factura', 'Número de CCF o Factura', 'TEXT'],
            ['monto_venta', 'Monto de la venta', 'DECIMAL'],
            ['debito_fiscal', 'Débito Fiscal', 'DECIMAL'],
            ['serie_comprobante_liquidacion', 'Serie de Comprobante de Liquidación', 'TEXT'],
            ['resolucion_comprobante_liquidacion', 'Resolución de Comprobante de Liquidación', 'TEXT'],
            ['numero_comprobante_liquidacion', 'Número de Comprobante de Liquidación', 'TEXT'],
            ['fecha_comprobante_liquidacion', 'Fecha de emisión Comprobante de Liquidación', 'DATE'],
            ['dui_mandante', 'DUI del Mandante', 'DUI'],
            ['numero_anexo', 'Número de Anexo', 'INTEGER'],
        ];

        $fields['A5'] = [
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['numero_identificacion_proveedor', 'Número de Documento de Identificación del proveedor', 'TEXT'],
            ['nombre_proveedor', 'Nombre del proveedor', 'TEXT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento_emitido', 'Número de documento emitido', 'TEXT'],
            ['monto_compra', 'Monto de la compra', 'DECIMAL'],
            ['retencion_iva_13', 'Retención IVA 13%', 'DECIMAL'],
            ['tipo_operacion_renta', 'Tipo de Operación (Renta)', 'TEXT'],
            ['clasificacion_renta', 'Clasificación (Renta)', 'TEXT'],
            ['sector_renta', 'Sector (Renta)', 'TEXT'],
            ['tipo_costo_gasto_renta', 'Tipo de Costo / Gasto (Renta)', 'TEXT'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A6'] = [
            ['nit_agente', 'NIT Agente', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento', 'Número de Documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_anticipo_2', 'Monto Anticipo a Cuenta 2%', 'DECIMAL'],
            ['dui_agente', 'DUI Agente', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A7'] = [
            ['nit_agente', 'NIT Agente', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento', 'Número de documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_retencion_1', 'Monto Retención 1%', 'DECIMAL'],
            ['dui_agente', 'DUI Agente', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A8'] = [
            ['nit_agente', 'NIT Agente', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento', 'Número de documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_percepcion_1', 'Monto de percepción 1%', 'DECIMAL'],
            ['dui_agente', 'DUI Agente', 'DUI'],
            ['numero_anexo', 'Número de Anexo', 'INTEGER'],
        ];

        $fields['A9'] = [
            ['nit_sujeto', 'NIT Sujeto', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['resolucion', 'Resolución', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento_control', 'Número de documento/Número de Control', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_percepcion_1', 'Monto Percepción 1%', 'DECIMAL'],
            ['dui_sujeto', 'DUI Sujeto', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A10'] = [
            ['nit_sujeto', 'NIT Sujeto', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['resolucion', 'Resolución', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento', 'Número de documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_retencion_1', 'Monto Retención 1%', 'DECIMAL'],
            ['dui_sujeto', 'DUI Sujeto', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A11'] = [
            ['nit_sujeto', 'NIT Sujeto', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['resolucion', 'Resolución', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['numero_documento', 'Número de Documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_anticipo_2', 'Monto del Anticipo a Cuenta 2%', 'DECIMAL'],
            ['dui_sujeto', 'DUI Sujeto', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        $fields['A12'] = [
            ['nit_sujeto', 'NIT Sujeto', 'NIT'],
            ['fecha_emision', 'Fecha de emisión', 'DATE'],
            ['tipo_documento', 'Tipo de documento', 'TEXT'],
            ['serie', 'Serie', 'TEXT'],
            ['resolucion', 'Resolución', 'TEXT'],
            ['numero_documento', 'Número de documento', 'TEXT'],
            ['monto_sujeto', 'Monto sujeto', 'DECIMAL'],
            ['monto_retencion_13', 'Monto Retención 13%', 'DECIMAL'],
            ['dui_sujeto', 'DUI Sujeto', 'DUI'],
            ['numero_anexo', 'Número de anexo', 'INTEGER'],
        ];

        foreach ($fields as $annexCode => $annexFields) {
            if (!isset($annexes[$annexCode])) {
                continue;
            }

            foreach ($annexFields as $index => $field) {
                DB::table('tax_annex_fields')->updateOrInsert(
                    [
                        'tax_annex_id' => $annexes[$annexCode],
                        'field_code' => $field[0],
                    ],
                    [
                        'field_order' => $index + 1,
                        'field_name' => $field[1],
                        'data_type' => $field[2],
                        'required' => false,
                        'active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
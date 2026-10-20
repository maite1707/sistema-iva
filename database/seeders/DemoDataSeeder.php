<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\IvaMovement;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\ThirdPartySale;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::orderBy('id')->first();

        if (! $user) {
            $this->command->error('No hay ningún usuario registrado.');
            return;
        }

        foreach ($this->companies() as $data) {
            $this->seedCompany($data, $user);
        }

        $this->command->info('Datos de demostración listos.');
    }

    private function seedCompany(array $data, User $user): void
    {
        if (Company::where('nit', $data['company']['nit'])->exists()) {
            $this->command->warn($data['company']['name'] . ' ya existe, se omite.');
            return;
        }

        $company = Company::create($data['company'] + ['iva_taxpayer' => true, 'status' => true]);
        $company->users()->attach($user->id, ['is_admin' => true, 'status' => true]);

        $customers = [];
        foreach ($data['customers'] as $c) {
            $customers[] = Customer::create($c + ['company_id' => $company->id, 'status' => true]);
        }

        $suppliers = [];
        foreach ($data['suppliers'] as $s) {
            $suppliers[] = Supplier::create($s + ['company_id' => $company->id, 'status' => true]);
        }

        $docs = DocumentType::pluck('id', 'code');

        foreach ($data['purchases'] as [$sup, $doc, $number, $date, $taxable, $exempt]) {
            $iva = round($taxable * 0.13, 2);
            Purchase::create([
                'company_id' => $company->id,
                'supplier_id' => $suppliers[$sup]->id,
                'document_type_id' => $docs[$doc],
                'document_number' => $number,
                'issue_date' => $date,
                'taxable_amount' => $taxable,
                'exempt_amount' => $exempt,
                'iva_amount' => $iva,
                'total' => $taxable + $exempt + $iva,
                'status' => true,
                'is_excluded_subject' => false,
            ]);
        }

        foreach ($data['excluded'] as [$sup, $number, $date, $taxable, $op, $class, $sector, $cost]) {
            $iva = 0;
            Purchase::create([
                'company_id' => $company->id,
                'supplier_id' => $suppliers[$sup]->id,
                'document_type_id' => $docs['14'],
                'document_number' => $number,
                'issue_date' => $date,
                'taxable_amount' => $taxable,
                'exempt_amount' => 0,
                'iva_amount' => $iva,
                'total' => $taxable + $iva,
                'status' => true,
                'is_excluded_subject' => true,
                'retention_amount' => round($taxable * 0.13, 2),
                'renta_operation_type' => $op,
                'renta_classification' => $class,
                'renta_sector' => $sector,
                'renta_cost_type' => $cost,
            ]);
        }

        foreach ($data['sales'] as [$cus, $doc, $number, $date, $taxable, $exempt]) {
            $customer = $customers[$cus];
            $iva = round($taxable * 0.13, 2);
            Sale::create([
                'company_id' => $company->id,
                'customer_id' => $customer->id,
                'document_type_id' => $docs[$doc],
                'document_number' => $number,
                'issue_date' => $date,
                'taxable_amount' => $taxable,
                'exempt_amount' => $exempt,
                'iva_amount' => $iva,
                'total' => $taxable + $exempt + $iva,
                'status' => true,
                'taxpayer_type_snapshot' => $customer->taxpayer_type,
            ]);
        }

        foreach ($data['movements'] as $m) {
            IvaMovement::create($m + ['company_id' => $company->id, 'status' => true]);
        }

        foreach ($data['third_party'] as $t) {
            ThirdPartySale::create($t + ['company_id' => $company->id, 'status' => true]);
        }

        $this->command->info($data['company']['name'] . ' creada.');
    }

    private function companies(): array
    {
        return [
            [
                'company' => [
                    'name' => 'Ferretería El Tornillo S.A. de C.V.',
                    'commercial_name' => 'Ferretería El Tornillo',
                    'nit' => '0614-150390-101-2',
                    'nrc' => '112233-4',
                    'economic_activity' => 'Venta al por menor de artículos de ferretería',
                    'address' => 'Calle Principal #12, Colonia Escalón',
                    'department' => 'San Salvador',
                    'municipality' => 'San Salvador',
                    'phone' => '2222-3344',
                    'email' => 'contacto@eltornillo.example',
                ],
                'customers' => [
                    ['name' => 'Constructora Los Pinos S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-100588-103-4', 'nrc' => '334455-6', 'taxpayer_type' => 'CONTRIBUYENTE', 'phone' => '2255-1020', 'address' => 'Blvd. Los Próceres #45', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'Inversiones Maya S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-300691-101-7', 'nrc' => '445566-7', 'taxpayer_type' => 'CONTRIBUYENTE', 'phone' => '2260-3344', 'address' => 'Av. Roosevelt #200', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'Carlos Alberto Mejía', 'document_type' => 'DUI', 'document_number' => '03456789-1', 'taxpayer_type' => 'CONSUMIDOR_FINAL', 'phone' => '7845-1122', 'address' => 'Col. Miramonte', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'María José Hernández', 'document_type' => 'DUI', 'document_number' => '04567891-2', 'taxpayer_type' => 'CONSUMIDOR_FINAL', 'phone' => '7712-3344', 'address' => 'Col. Escalón', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                ],
                'suppliers' => [
                    ['name' => 'Distribuidora Industrial S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-050480-104-1', 'nrc' => '556677-8', 'is_iva_withholding_agent' => false, 'phone' => '2280-4455', 'address' => 'Zona Industrial Plan de la Laguna', 'department' => 'La Libertad', 'municipality' => 'Antiguo Cuscatlán'],
                    ['name' => 'Importadora Herramientas Pro S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-120785-105-9', 'nrc' => '667788-9', 'is_iva_withholding_agent' => true, 'phone' => '2270-8800', 'address' => 'Km 10 Carretera a Comalapa', 'department' => 'La Paz', 'municipality' => 'San Luis Talpa'],
                    ['name' => 'Dr. Roberto Alfaro', 'document_type' => 'DUI', 'document_number' => '02345678-9', 'is_iva_withholding_agent' => false, 'phone' => '7790-5566', 'address' => 'Col. Médica', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                ],
                'purchases' => [
                    [0, '03', 'CCF-004501', '2026-09-03', 850.00, 0],
                    [1, '03', 'CCF-001208', '2026-09-12', 1240.50, 0],
                    [0, '03', 'CCF-004577', '2026-09-24', 530.00, 25.00],
                    [1, '03', 'CCF-001263', '2026-10-05', 960.00, 0],
                    [0, '03', 'CCF-004630', '2026-10-11', 410.75, 0],
                ],
                'excluded' => [
                    [2, 'FSE-0001', '2026-10-07', 150.00, 'Servicios profesionales', 'Gasto', 'Servicios', 'Gastos de administración'],
                ],
                'sales' => [
                    [0, '03', 'CCF-000101', '2026-09-04', 1500.00, 0],
                    [1, '03', 'CCF-000102', '2026-09-10', 780.00, 0],
                    [2, '01', 'FAC-000501', '2026-09-15', 120.00, 0],
                    [3, '01', 'FAC-000502', '2026-09-22', 85.50, 0],
                    [0, '03', 'CCF-000103', '2026-10-02', 2100.00, 0],
                    [1, '03', 'CCF-000104', '2026-10-08', 640.00, 0],
                    [2, '01', 'FAC-000503', '2026-10-09', 45.00, 0],
                    [3, '01', 'FAC-000504', '2026-10-13', 210.00, 0],
                ],
                'movements' => [
                    ['movement_type' => 'RETENCION', 'direction' => 'EMITIDO', 'percentage' => 1.00, 'counterparty_nit' => '0614-120785-105-9', 'counterparty_name' => 'Importadora Herramientas Pro S.A. de C.V.', 'document_type' => 'Comprobante de Retención', 'document_series' => 'RET', 'document_number' => 'RET-0101', 'issue_date' => '2026-10-05', 'taxable_amount' => 960.00, 'tax_amount' => 9.60],
                    ['movement_type' => 'ANTICIPO', 'direction' => 'RECIBIDO', 'percentage' => 2.00, 'counterparty_nit' => '0614-100588-103-4', 'counterparty_name' => 'Constructora Los Pinos S.A. de C.V.', 'document_series' => 'ANT', 'document_number' => 'ANT-0045', 'issue_date' => '2026-09-04', 'taxable_amount' => 1500.00, 'tax_amount' => 30.00],
                ],
                'third_party' => [
                    ['mandante_nit_nrc' => '0210-180383-101-6', 'mandante_name' => 'Textiles Santa Ana S.A. de C.V.', 'document_type' => 'CCF', 'document_number' => 'CCF-000105', 'document_date' => '2026-10-10', 'sale_amount' => 400.00, 'tax_amount' => 52.00, 'settlement_number' => 'LIQ-0001', 'settlement_date' => '2026-10-14'],
                ],
            ],
            [
                'company' => [
                    'name' => 'Tecnología Cuscatlán S.A. de C.V.',
                    'commercial_name' => 'TecCuscatlán',
                    'nit' => '0614-220792-102-5',
                    'nrc' => '223344-5',
                    'economic_activity' => 'Servicios de consultoría informática',
                    'address' => 'Centro Comercial Plaza Merliot, Local 8',
                    'department' => 'La Libertad',
                    'municipality' => 'Santa Tecla',
                    'phone' => '2288-1100',
                    'email' => 'info@teccuscatlan.example',
                ],
                'customers' => [
                    ['name' => 'Grupo Financiero Lempa S.A.', 'document_type' => 'NIT', 'document_number' => '0614-010170-100-3', 'nrc' => '778899-0', 'taxpayer_type' => 'CONTRIBUYENTE', 'phone' => '2200-1000', 'address' => 'Paseo General Escalón #3000', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'Clínica Santa Lucía S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-250976-102-8', 'nrc' => '889900-1', 'taxpayer_type' => 'CONTRIBUYENTE', 'phone' => '2244-5566', 'address' => 'Col. Médica #18', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'Ana Beatriz Rivas', 'document_type' => 'DUI', 'document_number' => '05678912-3', 'taxpayer_type' => 'CONSUMIDOR_FINAL', 'phone' => '7633-2211', 'address' => 'Santa Tecla', 'department' => 'La Libertad', 'municipality' => 'Santa Tecla'],
                    ['name' => 'José Luis Campos', 'document_type' => 'DUI', 'document_number' => '06789123-4', 'taxpayer_type' => 'CONSUMIDOR_FINAL', 'phone' => '7544-8899', 'address' => 'Antiguo Cuscatlán', 'department' => 'La Libertad', 'municipality' => 'Antiguo Cuscatlán'],
                ],
                'suppliers' => [
                    ['name' => 'Telecom Sur S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-081088-101-0', 'nrc' => '990011-2', 'is_iva_withholding_agent' => false, 'phone' => '2500-0000', 'address' => 'Col. Escalón', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                    ['name' => 'Equipos y Cómputo S.A. de C.V.', 'document_type' => 'NIT', 'document_number' => '0614-170492-103-5', 'nrc' => '001122-3', 'is_iva_withholding_agent' => true, 'phone' => '2266-7788', 'address' => 'Blvd. del Ejército', 'department' => 'San Salvador', 'municipality' => 'Soyapango'],
                    ['name' => 'Licda. Patricia Moreno', 'document_type' => 'DUI', 'document_number' => '01234567-8', 'is_iva_withholding_agent' => false, 'phone' => '7811-4433', 'address' => 'Col. Flor Blanca', 'department' => 'San Salvador', 'municipality' => 'San Salvador'],
                ],
                'purchases' => [
                    [0, '03', 'CCF-007801', '2026-09-02', 180.00, 0],
                    [1, '03', 'CCF-002190', '2026-09-18', 2450.00, 0],
                    [0, '03', 'CCF-007912', '2026-10-02', 180.00, 0],
                    [1, '03', 'CCF-002305', '2026-10-09', 1320.40, 0],
                ],
                'excluded' => [
                    [2, 'FSE-0007', '2026-10-06', 300.00, 'Servicios profesionales', 'Costo', 'Servicios', 'Honorarios profesionales'],
                ],
                'sales' => [
                    [0, '03', 'CCF-000301', '2026-09-06', 3200.00, 0],
                    [1, '03', 'CCF-000302', '2026-09-20', 1850.00, 0],
                    [2, '01', 'FAC-000801', '2026-09-25', 95.00, 0],
                    [0, '03', 'CCF-000303', '2026-10-03', 3200.00, 0],
                    [1, '03', 'CCF-000304', '2026-10-12', 2100.00, 0],
                    [3, '01', 'FAC-000802', '2026-10-14', 60.00, 0],
                ],
                'movements' => [
                    ['movement_type' => 'RETENCION', 'direction' => 'RECIBIDO', 'percentage' => 1.00, 'counterparty_nit' => '0614-010170-100-3', 'counterparty_name' => 'Grupo Financiero Lempa S.A.', 'document_type' => 'Comprobante de Retención', 'document_series' => 'RET', 'document_number' => 'RET-5521', 'issue_date' => '2026-10-03', 'taxable_amount' => 3200.00, 'tax_amount' => 32.00],
                ],
                'third_party' => [],
            ],
        ];
    }
}
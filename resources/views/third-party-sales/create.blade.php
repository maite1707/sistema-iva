<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Nueva Venta a Cuenta de Terceros</flux:heading>
            <flux:subheading>Anexo 4 — {{ session('company_name') }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('third-party-sales.store') }}" class="flex max-w-2xl flex-col gap-6">
            @csrf

            <flux:heading size="lg">Datos del mandante</flux:heading>

            <flux:input name="mandante_name" label="Nombre del mandante" required />
            <flux:input name="mandante_nit_nrc" label="NIT o NRC del mandante" />
            <flux:input name="mandante_dui" label="DUI del mandante (si no tiene NIT/NRC)" />

            <flux:heading size="lg">Documento emitido al cliente final</flux:heading>

            <flux:select name="document_type" label="Tipo de documento">
                <flux:select.option value="Factura">Factura</flux:select.option>
                <flux:select.option value="CCF">Comprobante de Crédito Fiscal</flux:select.option>
            </flux:select>
            <flux:input name="document_series" label="Serie" />
            <flux:input name="document_resolution" label="Resolución" />
            <flux:input name="document_number" label="Número de documento" />
            <flux:input type="date" name="document_date" label="Fecha de emisión" required />
            <flux:input type="number" step="0.01" name="sale_amount" label="Monto de la venta" required />

            <flux:text size="sm" class="text-neutral-500">
                El débito fiscal (13%) se calcula automáticamente al guardar.
            </flux:text>

            <flux:heading size="lg">Comprobante de Liquidación (opcional, puedes dejarlo vacío y registrarlo después)</flux:heading>

            <flux:input name="settlement_series" label="Serie de liquidación" />
            <flux:input name="settlement_resolution" label="Resolución de liquidación" />
            <flux:input name="settlement_number" label="Número de liquidación" />
            <flux:input type="date" name="settlement_date" label="Fecha de liquidación" />

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">Guardar</flux:button>
                <flux:button :href="route('third-party-sales.index')" variant="ghost">Cancelar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
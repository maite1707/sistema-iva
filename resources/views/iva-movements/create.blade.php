<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Nuevo movimiento de IVA</flux:heading>
            <flux:subheading>Retenciones, percepciones y anticipos — {{ session('company_name') }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('iva-movements.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:select name="annex_code" label="Tipo de movimiento (Anexo)" required>
                <flux:select.option value="">Selecciona el tipo</flux:select.option>
                <flux:select.option value="A6">A6 — Anticipo 2% que me hicieron (recibido)</flux:select.option>
                <flux:select.option value="A7">A7 — Retención 1% que me hicieron (recibido)</flux:select.option>
                <flux:select.option value="A8">A8 — Percepción 1% que me hicieron (recibido)</flux:select.option>
                <flux:select.option value="A9">A9 — Percepción 1% que yo hice (emitido)</flux:select.option>
                <flux:select.option value="A10">A10 — Retención 1% que yo hice (emitido)</flux:select.option>
                <flux:select.option value="A11">A11 — Anticipo 2% que yo hice (emitido)</flux:select.option>
                <flux:select.option value="A12">A12 — Retención 13% que yo hice (emitido)</flux:select.option>
            </flux:select>

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="counterparty_name"
                    label="Nombre de la contraparte"
                    value="{{ old('counterparty_name') }}"
                />

                <flux:input
                    name="counterparty_nit"
                    label="NIT de la contraparte"
                    value="{{ old('counterparty_nit') }}"
                />

                <flux:input
                    name="counterparty_dui"
                    label="DUI de la contraparte (si aplica)"
                    value="{{ old('counterparty_dui') }}"
                />

                <flux:input
                    type="date"
                    name="issue_date"
                    label="Fecha de emisión"
                    value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                    required
                />

                <flux:input
                    name="document_type"
                    label="Tipo de documento"
                    value="{{ old('document_type') }}"
                />

                <flux:input
                    name="document_resolution"
                    label="Resolución"
                    value="{{ old('document_resolution') }}"
                />

                <flux:input
                    name="document_series"
                    label="Serie"
                    value="{{ old('document_series') }}"
                />

                <flux:input
                    name="document_number"
                    label="Número de documento"
                    value="{{ old('document_number') }}"
                />

                <flux:input
                    type="number"
                    step="0.01"
                    min="0"
                    name="taxable_amount"
                    label="Monto sujeto"
                    value="{{ old('taxable_amount', '0.00') }}"
                    required
                />
            </div>

            <flux:text size="xs" class="text-neutral-400">
                El monto de retención/percepción/anticipo se calcula automáticamente según el porcentaje del Anexo seleccionado.
            </flux:text>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Guardar movimiento
                </flux:button>

                <flux:button href="{{ route('iva-movements.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
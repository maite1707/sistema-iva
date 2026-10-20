<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Editar compra</flux:heading>
            <flux:subheading>{{ $purchase->supplier->name }} — {{ $purchase->document_number }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('purchases.update', $purchase) }}" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <flux:select name="supplier_id" label="Proveedor" required>
                    <flux:select.option value="">Selecciona un proveedor</flux:select.option>
                    @foreach ($suppliers as $supplier)
                        <flux:select.option
                            value="{{ $supplier->id }}"
                            :selected="old('supplier_id', $purchase->supplier_id) == $supplier->id"
                        >
                            {{ $supplier->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select name="document_type_id" label="Tipo de documento" required>
                    <flux:select.option value="">Selecciona un tipo</flux:select.option>
                    @foreach ($documentTypes as $documentType)
                        <flux:select.option
                            value="{{ $documentType->id }}"
                            :selected="old('document_type_id', $purchase->document_type_id) == $documentType->id"
                        >
                            {{ $documentType->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    name="document_number"
                    label="Número de documento"
                    value="{{ old('document_number', $purchase->document_number) }}"
                    required
                />

                <flux:input
                    type="date"
                    name="issue_date"
                    label="Fecha de emisión"
                    value="{{ old('issue_date', $purchase->issue_date->format('Y-m-d')) }}"
                    required
                />

                <flux:input
                    type="number"
                    step="0.01"
                    min="0"
                    name="taxable_amount"
                    label="Monto gravado"
                    value="{{ old('taxable_amount', $purchase->taxable_amount) }}"
                    id="taxable_amount"
                    required
                />

                <flux:input
                    type="number"
                    step="0.01"
                    min="0"
                    name="exempt_amount"
                    label="Monto exento"
                    value="{{ old('exempt_amount', $purchase->exempt_amount) }}"
                    id="exempt_amount"
                />
            </div>

         <div class="rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                <flux:checkbox
                    name="is_excluded_subject"
                    value="1"
                    label="Este proveedor es un Sujeto Excluido (Anexo 5)"
                    :checked="old('is_excluded_subject', $purchase->is_excluded_subject)"
                />

                <flux:text size="xs" class="mt-2 text-neutral-400">
                    Márcalo si el proveedor es un profesional sin NRC que emite "Factura de Sujeto Excluido". Se le calcula retención del 13% y no genera crédito fiscal.
                </flux:text>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <flux:input name="renta_operation_type" label="Tipo de Operación (Renta)" value="{{ old('renta_operation_type', $purchase->renta_operation_type) }}" />
                    <flux:input name="renta_classification" label="Clasificación (Renta)" value="{{ old('renta_classification', $purchase->renta_classification) }}" />
                    <flux:input name="renta_sector" label="Sector (Renta)" value="{{ old('renta_sector', $purchase->renta_sector) }}" />
                    <flux:input name="renta_cost_type" label="Tipo de Costo / Gasto (Renta)" value="{{ old('renta_cost_type', $purchase->renta_cost_type) }}" />
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="grid gap-2 text-sm md:grid-cols-3">
                    <div>
                        <span class="text-neutral-500">IVA (13%):</span>
                        <span class="font-medium" id="preview_iva">${{ number_format($purchase->iva_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500">Total estimado:</span>
                        <span class="font-medium" id="preview_total">${{ number_format($purchase->total, 2) }}</span>
                    </div>
                </div>
                <flux:text size="xs" class="mt-2 text-neutral-400">
                    Este cálculo es solo una vista previa. El monto real se calcula y valida en el servidor al guardar.
                </flux:text>
            </div>

            <flux:textarea
                name="notes"
                label="Observaciones"
                rows="2"
            >{{ old('notes', $purchase->notes) }}</flux:textarea>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Actualizar compra
                </flux:button>

                <flux:button href="{{ route('purchases.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>

    <script>
        function updatePreview() {
            const taxable = parseFloat(document.getElementById('taxable_amount').value) || 0;
            const exempt = parseFloat(document.getElementById('exempt_amount').value) || 0;
            const excluded = document.querySelector('input[name="is_excluded_subject"]').checked;
            const iva = excluded ? 0 : Math.round(taxable * 0.13 * 100) / 100;
            const total = taxable + exempt + iva;

            document.getElementById('preview_iva').textContent = '$' + iva.toFixed(2);
            document.getElementById('preview_total').textContent = '$' + total.toFixed(2);
        }

        document.getElementById('taxable_amount').addEventListener('input', updatePreview);
        document.getElementById('exempt_amount').addEventListener('input', updatePreview);
        document.querySelector('input[name="is_excluded_subject"]').addEventListener('change', updatePreview);
    </script>
</x-layouts.app>
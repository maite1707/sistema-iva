<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Compras</flux:heading>
                <flux:subheading>Compras registradas de {{ session('company_name') }}</flux:subheading>
            </div>

            <flux:button href="{{ route('purchases.create') }}" variant="primary">
                Nueva compra
            </flux:button>
        </div>

        @if (session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 text-left dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-neutral-500">Fecha</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Proveedor</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Gravado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">IVA</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Total</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Estado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-4 py-3">{{ $purchase->issue_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $purchase->supplier->name }}</td>
                            <td class="px-4 py-3">
                                <div>{{ $purchase->documentType->name }}</div>
                                <div class="text-xs text-neutral-500">{{ $purchase->document_number }}</div>
                            </td>
                            <td class="px-4 py-3">${{ number_format($purchase->taxable_amount, 2) }}</td>
                            <td class="px-4 py-3">${{ number_format($purchase->iva_amount, 2) }}</td>
                            <td class="px-4 py-3 font-medium">${{ number_format($purchase->total, 2) }}</td>
                            <td class="px-4 py-3">
                                @if($purchase->status)
                                    <flux:badge color="green" size="sm">Activa</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Anulada</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <flux:button
                                        href="{{ route('purchases.edit', $purchase) }}"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        Editar
                                    </flux:button>

                                    @if($purchase->status)
                                        <form method="POST" action="{{ route('purchases.destroy', $purchase) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                type="submit"
                                                size="sm"
                                                variant="danger"
                                                onclick="return confirm('¿Anular esta compra?')"
                                            >
                                                Anular
                                            </flux:button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-neutral-500">
                                Aún no tienes compras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $purchases->links() }}
        </div>
    </div>
</x-layouts.app>
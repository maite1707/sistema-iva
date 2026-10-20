<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Proveedores</flux:heading>
                <flux:subheading>Proveedores de {{ session('company_name') }}</flux:subheading>
            </div>

            <flux:button href="{{ route('suppliers.create') }}" variant="primary">
                Nuevo proveedor
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
                        <th class="px-4 py-3 font-medium text-neutral-500">Nombre</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Agente retenedor</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Estado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $supplier->name }}</div>
                                @if($supplier->commercial_name)
                                    <div class="text-xs text-neutral-500">{{ $supplier->commercial_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $supplier->document_type }}
                                @if($supplier->document_number)
                                    — {{ $supplier->document_number }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($supplier->is_iva_withholding_agent)
                                    <flux:badge color="amber" size="sm">Sí</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">No</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($supplier->status)
                                    <flux:badge color="green" size="sm">Activo</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Inactivo</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <flux:button
                                        href="{{ route('suppliers.edit', $supplier) }}"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        Editar
                                    </flux:button>

                                    @if($supplier->status)
                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                type="submit"
                                                size="sm"
                                                variant="danger"
                                                onclick="return confirm('¿Desactivar este proveedor?')"
                                            >
                                                Desactivar
                                            </flux:button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-neutral-500">
                                Aún no tienes proveedores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $suppliers->links() }}
        </div>
    </div>
</x-layouts.app>
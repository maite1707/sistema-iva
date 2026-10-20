<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Clientes</flux:heading>
                <flux:subheading>Clientes de {{ session('company_name') }}</flux:subheading>
            </div>

            <flux:button href="{{ route('customers.create') }}" variant="primary">
                Nuevo cliente
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
                        <th class="px-4 py-3 font-medium text-neutral-500">Tipo</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Estado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $customer->name }}</div>
                                @if($customer->commercial_name)
                                    <div class="text-xs text-neutral-500">{{ $customer->commercial_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $customer->document_type }}
                                @if($customer->document_number)
                                    — {{ $customer->document_number }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($customer->taxpayer_type === 'CONTRIBUYENTE')
                                    <flux:badge color="blue" size="sm">Contribuyente</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Consumidor final</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($customer->status)
                                    <flux:badge color="green" size="sm">Activo</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Inactivo</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <flux:button
                                        href="{{ route('customers.edit', $customer) }}"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        Editar
                                    </flux:button>

                                    @if($customer->status)
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                type="submit"
                                                size="sm"
                                                variant="danger"
                                                onclick="return confirm('¿Desactivar este cliente?')"
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
                                Aún no tienes clientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $customers->links() }}
        </div>
    </div>
</x-layouts.app>
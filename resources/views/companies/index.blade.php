<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Empresas</flux:heading>
                <flux:subheading>Empresas asociadas a tu cuenta</flux:subheading>
            </div>

            <flux:button href="{{ route('companies.create') }}" variant="primary">
                Nueva empresa
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
                        <th class="px-4 py-3 font-medium text-neutral-500">NRC</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">NIT</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Estado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($companies as $company)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $company->name }}</div>
                                @if($company->commercial_name)
                                    <div class="text-xs text-neutral-500">{{ $company->commercial_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $company->nrc ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $company->nit ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($company->status)
                                    <flux:badge color="green" size="sm">Activa</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Inactiva</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <flux:button
                                        href="{{ route('companies.edit', $company) }}"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        Editar
                                    </flux:button>

                                    @if($company->status)
                                        <form method="POST" action="{{ route('companies.destroy', $company) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                type="submit"
                                                size="sm"
                                                variant="danger"
                                                onclick="return confirm('¿Desactivar esta empresa?')"
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
                                Aún no tienes empresas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $companies->links() }}
        </div>
    </div>
</x-layouts.app>
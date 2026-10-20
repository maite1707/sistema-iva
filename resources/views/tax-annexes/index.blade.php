<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Anexos F-07</flux:heading>
            <flux:subheading>Generación de anexos para {{ session('company_name') }}</flux:subheading>
        </div>

        @if (session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if (session('warning'))
            <flux:callout variant="warning" icon="exclamation-triangle">
                {{ session('warning') }}
            </flux:callout>
        @endif

        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <flux:heading size="lg" class="mb-4">Generar nuevo anexo</flux:heading>

            <form method="POST" action="{{ route('tax-annexes.generate') }}" class="flex flex-wrap items-end gap-4">
                @csrf
                <flux:select name="annex_code" label="Anexo" class="w-64">
                    @foreach ($annexes->whereIn('code', ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12']) as $annex)
                        <flux:select.option value="{{ $annex->code }}">
                            {{ $annex->code }} — {{ $annex->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select name="month" label="Mes" class="w-40">
                    @foreach ([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'] as $num => $label)
                        <flux:select.option value="{{ $num }}" :selected="$num == now()->month">
                            {{ $label }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select name="year" label="Año" class="w-32">
                    @for ($y = now()->year; $y >= now()->year - 3; $y--)
                        <flux:select.option value="{{ $y }}" :selected="$y == now()->year">
                            {{ $y }}
                        </flux:select.option>
                    @endfor
                </flux:select>

                <flux:button type="submit" variant="primary">
                    Generar anexo
                </flux:button>
            </form>

            <flux:text size="xs" class="mt-3 text-neutral-400">
                Disponibles: A1 (Ventas a Contribuyentes), A2 (Ventas a Consumidor Final), A3 (Compras), A6-A12 (Anticipos, retenciones y percepciones de IVA).
            </flux:text>
        </div>

        <div>
            <flux:heading size="lg" class="mb-4">Historial de generaciones</flux:heading>

            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 text-left dark:bg-neutral-800">
                        <tr>
                            <th class="px-4 py-3 font-medium text-neutral-500">Fecha de generación</th>
                            <th class="px-4 py-3 font-medium text-neutral-500">Anexo</th>
                            <th class="px-4 py-3 font-medium text-neutral-500">Período</th>
                            <th class="px-4 py-3 font-medium text-neutral-500">Registros</th>
                            <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($generations as $generation)
                            <tr>
                                <td class="px-4 py-3">{{ $generation->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $generation->taxAnnex->code }} — {{ $generation->taxAnnex->name }}</td>
                                <td class="px-4 py-3">{{ str_pad($generation->period_month, 2, '0', STR_PAD_LEFT) }}/{{ $generation->period_year }}</td>
                                <td class="px-4 py-3">{{ $generation->total_records }}</td>
                                <td class="px-4 py-3">
                                    <flux:button
                                        href="{{ route('tax-annexes.download', $generation) }}"
                                        size="sm"
                                        variant="primary"
                                    >
                                        Descargar Excel
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-neutral-500">
                                    Aún no has generado ningún anexo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
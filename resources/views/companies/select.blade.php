<x-layouts.auth.simple :title="__('Selecciona una empresa')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <flux:heading size="lg">Selecciona una empresa</flux:heading>
            <flux:subheading>Elige con cuál empresa deseas trabajar</flux:subheading>
        </div>

        @if (session('warning'))
            <flux:callout variant="warning" icon="exclamation-triangle">
                {{ session('warning') }}
            </flux:callout>
        @endif

        @if ($companies->isEmpty())
            <flux:callout variant="secondary" icon="information-circle">
                Aún no perteneces a ninguna empresa activa.
            </flux:callout>

            <flux:button href="{{ route('companies.create') }}" variant="primary">
                Registrar mi primera empresa
            </flux:button>
        @else
            <div class="flex flex-col gap-3">
                @foreach ($companies as $company)
                    <form method="POST" action="{{ route('companies.select.store') }}">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->id }}">

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-neutral-200 p-4 text-left transition hover:border-neutral-400 dark:border-neutral-700 dark:hover:border-neutral-500"
                        >
                            <div class="font-medium">{{ $company->name }}</div>
                            @if($company->commercial_name)
                                <div class="text-sm text-neutral-500">{{ $company->commercial_name }}</div>
                            @endif
                            @if($company->nrc)
                                <div class="text-xs text-neutral-400">NRC: {{ $company->nrc }}</div>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>

            <flux:button href="{{ route('companies.create') }}" variant="ghost">
                + Registrar otra empresa
            </flux:button>
        @endif
    </div>
</x-layouts.auth.simple>
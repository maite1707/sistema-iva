<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Nuevo cliente</flux:heading>
            <flux:subheading>Registra un cliente para {{ session('company_name') }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('customers.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="name"
                    label="Nombre / Razón social"
                    value="{{ old('name') }}"
                    required
                />

                <flux:input
                    name="commercial_name"
                    label="Nombre comercial"
                    value="{{ old('commercial_name') }}"
                />

                <flux:select name="document_type" label="Tipo de documento" required>
                    @foreach (['NIT', 'DUI', 'NRC', 'PASAPORTE', 'CARNET_RESIDENTE', 'OTRO'] as $type)
                        <flux:select.option
                            value="{{ $type }}"
                            :selected="old('document_type', 'DUI') === $type"
                        >
                            {{ $type }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    name="document_number"
                    label="Número de documento"
                    value="{{ old('document_number') }}"
                />

                <flux:input
                    name="nrc"
                    label="NRC (si aplica)"
                    value="{{ old('nrc') }}"
                />

                <flux:select name="taxpayer_type" label="Tipo de contribuyente" required>
                    <flux:select.option
                        value="CONSUMIDOR_FINAL"
                        :selected="old('taxpayer_type', 'CONSUMIDOR_FINAL') === 'CONSUMIDOR_FINAL'"
                    >
                        Consumidor final
                    </flux:select.option>
                    <flux:select.option
                        value="CONTRIBUYENTE"
                        :selected="old('taxpayer_type') === 'CONTRIBUYENTE'"
                    >
                        Contribuyente
                    </flux:select.option>
                </flux:select>

                <flux:input
                    name="phone"
                    label="Teléfono"
                    value="{{ old('phone') }}"
                />

                <flux:input
                    name="email"
                    label="Correo electrónico"
                    type="email"
                    value="{{ old('email') }}"
                />

                <flux:input
                    name="department"
                    label="Departamento"
                    value="{{ old('department') }}"
                />

                <flux:input
                    name="municipality"
                    label="Municipio"
                    value="{{ old('municipality') }}"
                />
            </div>

            <flux:textarea
                name="address"
                label="Dirección"
                rows="2"
            >{{ old('address') }}</flux:textarea>

            <flux:checkbox
                name="status"
                label="Cliente activo"
                checked
            />

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Guardar cliente
                </flux:button>

                <flux:button href="{{ route('customers.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
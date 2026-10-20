<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Editar cliente</flux:heading>
            <flux:subheading>{{ $customer->name }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('customers.update', $customer) }}" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="name"
                    label="Nombre / Razón social"
                    value="{{ old('name', $customer->name) }}"
                    required
                />

                <flux:input
                    name="commercial_name"
                    label="Nombre comercial"
                    value="{{ old('commercial_name', $customer->commercial_name) }}"
                />

                <flux:select name="document_type" label="Tipo de documento" required>
                    @foreach (['NIT', 'DUI', 'NRC', 'PASAPORTE', 'CARNET_RESIDENTE', 'OTRO'] as $type)
                        <flux:select.option
                            value="{{ $type }}"
                            :selected="old('document_type', $customer->document_type) === $type"
                        >
                            {{ $type }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    name="document_number"
                    label="Número de documento"
                    value="{{ old('document_number', $customer->document_number) }}"
                />

                <flux:input
                    name="nrc"
                    label="NRC (si aplica)"
                    value="{{ old('nrc', $customer->nrc) }}"
                />

                <flux:select name="taxpayer_type" label="Tipo de contribuyente" required>
                    <flux:select.option
                        value="CONSUMIDOR_FINAL"
                        :selected="old('taxpayer_type', $customer->taxpayer_type) === 'CONSUMIDOR_FINAL'"
                    >
                        Consumidor final
                    </flux:select.option>
                    <flux:select.option
                        value="CONTRIBUYENTE"
                        :selected="old('taxpayer_type', $customer->taxpayer_type) === 'CONTRIBUYENTE'"
                    >
                        Contribuyente
                    </flux:select.option>
                </flux:select>

                <flux:input
                    name="phone"
                    label="Teléfono"
                    value="{{ old('phone', $customer->phone) }}"
                />

                <flux:input
                    name="email"
                    label="Correo electrónico"
                    type="email"
                    value="{{ old('email', $customer->email) }}"
                />

                <flux:input
                    name="department"
                    label="Departamento"
                    value="{{ old('department', $customer->department) }}"
                />

                <flux:input
                    name="municipality"
                    label="Municipio"
                    value="{{ old('municipality', $customer->municipality) }}"
                />
            </div>

            <flux:textarea
                name="address"
                label="Dirección"
                rows="2"
            >{{ old('address', $customer->address) }}</flux:textarea>

            <flux:checkbox
                name="status"
                label="Cliente activo"
                :checked="old('status', $customer->status)"
            />

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Actualizar cliente
                </flux:button>

                <flux:button href="{{ route('customers.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Nueva empresa</flux:heading>
            <flux:subheading>Registra los datos de tu empresa</flux:subheading>
        </div>

        <form method="POST" action="{{ route('companies.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="name"
                    label="Razón social"
                    value="{{ old('name') }}"
                    required
                />

                <flux:input
                    name="commercial_name"
                    label="Nombre comercial"
                    value="{{ old('commercial_name') }}"
                />

                <flux:input
                    name="nit"
                    label="NIT"
                    value="{{ old('nit') }}"
                />

                <flux:input
                    name="nrc"
                    label="NRC"
                    value="{{ old('nrc') }}"
                />

                <flux:input
                    name="dui"
                    label="DUI"
                    value="{{ old('dui') }}"
                />

                <flux:input
                    name="economic_activity"
                    label="Actividad económica"
                    value="{{ old('economic_activity') }}"
                />

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

            <div class="flex flex-col gap-3">
                <flux:checkbox
                    name="iva_taxpayer"
                    label="Contribuyente de IVA"
                    checked
                />

                <flux:checkbox
                    name="status"
                    label="Empresa activa"
                    checked
                />
            </div>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Guardar empresa
                </flux:button>

                <flux:button href="{{ route('companies.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
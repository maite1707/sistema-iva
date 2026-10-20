<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Editar empresa</flux:heading>
            <flux:subheading>{{ $company->name }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('companies.update', $company) }}" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="name"
                    label="Razón social"
                    value="{{ old('name', $company->name) }}"
                    required
                />

                <flux:input
                    name="commercial_name"
                    label="Nombre comercial"
                    value="{{ old('commercial_name', $company->commercial_name) }}"
                />

                <flux:input
                    name="nit"
                    label="NIT"
                    value="{{ old('nit', $company->nit) }}"
                />

                <flux:input
                    name="nrc"
                    label="NRC"
                    value="{{ old('nrc', $company->nrc) }}"
                />

                <flux:input
                    name="dui"
                    label="DUI"
                    value="{{ old('dui', $company->dui) }}"
                />

                <flux:input
                    name="economic_activity"
                    label="Actividad económica"
                    value="{{ old('economic_activity', $company->economic_activity) }}"
                />

                <flux:input
                    name="phone"
                    label="Teléfono"
                    value="{{ old('phone', $company->phone) }}"
                />

                <flux:input
                    name="email"
                    label="Correo electrónico"
                    type="email"
                    value="{{ old('email', $company->email) }}"
                />

                <flux:input
                    name="department"
                    label="Departamento"
                    value="{{ old('department', $company->department) }}"
                />

                <flux:input
                    name="municipality"
                    label="Municipio"
                    value="{{ old('municipality', $company->municipality) }}"
                />
            </div>

            <flux:textarea
                name="address"
                label="Dirección"
                rows="2"
            >{{ old('address', $company->address) }}</flux:textarea>

            <div class="flex flex-col gap-3">
                <flux:checkbox
                    name="iva_taxpayer"
                    label="Contribuyente de IVA"
                    :checked="old('iva_taxpayer', $company->iva_taxpayer)"
                />

                <flux:checkbox
                    name="status"
                    label="Empresa activa"
                    :checked="old('status', $company->status)"
                />
            </div>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Actualizar empresa
                </flux:button>

                <flux:button href="{{ route('companies.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
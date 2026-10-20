<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Editar proveedor</flux:heading>
            <flux:subheading>{{ $supplier->name }}</flux:subheading>
        </div>

        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    name="name"
                    label="Nombre / Razón social"
                    value="{{ old('name', $supplier->name) }}"
                    required
                />

                <flux:input
                    name="commercial_name"
                    label="Nombre comercial"
                    value="{{ old('commercial_name', $supplier->commercial_name) }}"
                />

                <flux:select name="document_type" label="Tipo de documento" required>
                    @foreach (['NIT', 'DUI', 'NRC', 'PASAPORTE', 'CARNET_RESIDENTE', 'OTRO'] as $type)
                        <flux:select.option
                            value="{{ $type }}"
                            :selected="old('document_type', $supplier->document_type) === $type"
                        >
                            {{ $type }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    name="document_number"
                    label="Número de documento"
                    value="{{ old('document_number', $supplier->document_number) }}"
                />

                <flux:input
                    name="nrc"
                    label="NRC (si aplica)"
                    value="{{ old('nrc', $supplier->nrc) }}"
                />

                <flux:input
                    name="phone"
                    label="Teléfono"
                    value="{{ old('phone', $supplier->phone) }}"
                />

                <flux:input
                    name="email"
                    label="Correo electrónico"
                    type="email"
                    value="{{ old('email', $supplier->email) }}"
                />

                <flux:input
                    name="department"
                    label="Departamento"
                    value="{{ old('department', $supplier->department) }}"
                />

                <flux:input
                    name="municipality"
                    label="Municipio"
                    value="{{ old('municipality', $supplier->municipality) }}"
                />
            </div>

            <flux:textarea
                name="address"
                label="Dirección"
                rows="2"
            >{{ old('address', $supplier->address) }}</flux:textarea>

            <div class="flex flex-col gap-3">
                <flux:checkbox
                    name="is_iva_withholding_agent"
                    label="Es agente de retención de IVA"
                    :checked="old('is_iva_withholding_agent', $supplier->is_iva_withholding_agent)"
                />

                <flux:checkbox
                    name="status"
                    label="Proveedor activo"
                    :checked="old('status', $supplier->status)"
                />
            </div>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">
                    Actualizar proveedor
                </flux:button>

                <flux:button href="{{ route('suppliers.index') }}" variant="ghost">
                    Cancelar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            {{-- Marca del sistema --}}
            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center gap-3 px-1" wire:navigate>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-blue-800 text-white shadow-sm">
                    <flux:icon.receipt-percent class="size-5" />
                </span>
                <span class="grid leading-tight">
                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">Sistema IVA</span>
                    <span class="text-[11px] text-zinc-500">Control fiscal · El Salvador</span>
                </span>
            </a>

            {{-- Empresa activa --}}
            <a href="{{ route('companies.select') }}" wire:navigate
               class="block rounded-lg border border-zinc-200 bg-white px-3 py-2.5 transition hover:border-blue-400 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-blue-500">
                <div class="text-[11px] font-medium uppercase tracking-wide text-zinc-500">Empresa activa</div>
                <div class="mt-0.5 truncate text-sm font-semibold text-zinc-900 dark:text-white">
                    {{ session('company_name', 'Sin empresa seleccionada') }}
                </div>
                <div class="mt-1 flex items-center gap-1 text-xs text-blue-700 dark:text-blue-400">
                    <flux:icon.arrows-right-left class="size-3.5" />
                    Cambiar empresa
                </div>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="General" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navlist.item>
                    <flux:navlist.item icon="building-office" :href="route('companies.index')" :current="request()->routeIs('companies.*')" wire:navigate>Empresas</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Catálogos" class="grid">
                    <flux:navlist.item icon="users" :href="route('customers.index')" :current="request()->routeIs('customers.*')" wire:navigate>Clientes</flux:navlist.item>
                    <flux:navlist.item icon="truck" :href="route('suppliers.index')" :current="request()->routeIs('suppliers.*')" wire:navigate>Proveedores</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Operaciones" class="grid">
                    <flux:navlist.item icon="banknotes" :href="route('sales.index')" :current="request()->routeIs('sales.index') || request()->routeIs('sales.create') || request()->routeIs('sales.edit')" wire:navigate>Ventas</flux:navlist.item>
                    <flux:navlist.item icon="shopping-cart" :href="route('purchases.index')" :current="request()->routeIs('purchases.index') || request()->routeIs('purchases.create') || request()->routeIs('purchases.edit')" wire:navigate>Compras</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('third-party-sales.index')" :current="request()->routeIs('third-party-sales.*')" wire:navigate>Ventas a terceros</flux:navlist.item>
                    <flux:navlist.item icon="receipt-percent" :href="route('iva-movements.index')" :current="request()->routeIs('iva-movements.*')" wire:navigate>Retenciones y anticipos</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Libros de IVA" class="grid">
                    <flux:navlist.item icon="book-open" :href="route('sales.book')" :current="request()->routeIs('sales.book')" wire:navigate>Libro de ventas</flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('purchases.book')" :current="request()->routeIs('purchases.book')" wire:navigate>Libro de compras</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Declaración" class="grid">
                    <flux:navlist.item icon="document-text" :href="route('tax-annexes.index')" :current="request()->routeIs('tax-annexes.*')" wire:navigate>Anexos F-07</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Configuración</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            Cerrar sesión
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <span class="ml-2 text-sm font-semibold text-zinc-900 dark:text-white">Sistema IVA</span>

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Configuración</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            Cerrar sesión
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
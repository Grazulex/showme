<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-emerald-950/20 relative overflow-x-hidden">
        {{-- Background Pattern --}}
        <div class="fixed inset-0 -z-10 opacity-[0.02] dark:opacity-[0.03]">
            <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern" width="32" height="32" patternUnits="userSpaceOnUse">
                        <circle cx="16" cy="16" r="1" fill="currentColor"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
            </svg>
        </div>

        <flux:sidebar sticky stashable class="border-r border-emerald-200/50 bg-gradient-to-b from-white via-emerald-50/30 to-teal-50/20 dark:border-emerald-900/30 dark:from-zinc-900 dark:via-zinc-900/95 dark:to-emerald-950/40 backdrop-blur-sm shadow-xl shadow-emerald-500/5">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            {{-- Logo with gradient effect --}}
            <a href="{{ route('home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse group" wire:navigate>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <x-app-logo />
                </div>
            </a>

            <flux:navlist variant="outline" class="menu-modern">
                <flux:navlist.group :heading="__('Platform')" class="grid space-y-1">
                    <flux:navlist.item
                        icon="home"
                        :href="route('home')"
                        :current="request()->routeIs('home')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Dashboard') }}
                    </flux:navlist.item>
                    <flux:navlist.item
                        icon="tag"
                        :href="route('topics.index')"
                        :current="request()->routeIs('topics.*')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Topics') }}
                    </flux:navlist.item>
                    <flux:navlist.item
                        icon="medal"
                        :href="route('goals.index')"
                        :current="request()->routeIs('goals.*')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Goals') }}
                    </flux:navlist.item>
                    <flux:navlist.item
                        icon="presentation-chart-line"
                        :href="route('values.index')"
                        :current="request()->routeIs('values.*')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Values') }}
                    </flux:navlist.item>
                    <flux:navlist.item
                        icon="fire"
                        :href="route('meals.index')"
                        :current="request()->routeIs('meals.*')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-amber-500/10 hover:to-orange-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Meals') }}
                    </flux:navlist.item>
                    <flux:navlist.item
                        icon="user"
                        :href="route('configurations.index')"
                        :current="request()->routeIs('configurations.*')"
                        wire:navigate
                        class="menu-item-modern hover:bg-gradient-to-r hover:from-sky-500/10 hover:to-blue-500/10 transition-all duration-200 hover:translate-x-1"
                    >
                        {{ __('Configurations') }}
                    </flux:navlist.item>
                </flux:navlist.group>

            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <flux:profile
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevrons-up-down"
                        class="relative"
                    />
                </div>

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate class="hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200">
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full hover:bg-gradient-to-r hover:from-rose-500/10 hover:to-pink-500/10 transition-all duration-200">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate class="hover:bg-gradient-to-r hover:from-emerald-500/10 hover:to-teal-500/10 transition-all duration-200">
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full hover:bg-gradient-to-r hover:from-rose-500/10 hover:to-pink-500/10 transition-all duration-200">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        <flux:toast />
    </body>
</html>

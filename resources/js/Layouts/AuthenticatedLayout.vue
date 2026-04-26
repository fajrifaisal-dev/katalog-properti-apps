<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

const page = usePage()

// MENU ADMIN
const menus = [
    { name: 'Dashboard', icon: '🏠', href: route('dashboard') },
    { name: 'Kategori', icon: '📂', href: route('categories.index') },
    // sementara disable dulu
    { name: 'Katalog Properti', icon: '🏘️', href: route('properties.index') },
    { name: 'Booking Konsultasi', icon: '📅', href: '#' },
    { name: 'Data Klien', icon: '👥', href: '#' },
]

// helper untuk active menu
const isActive = (href) => {
    return page.url.startsWith(new URL(href, window.location.origin).pathname)
}
</script>

<template>
    <div class="flex min-h-screen bg-[#F8F8F8]">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-[#0B1F4A] text-white flex flex-col">

            <!-- LOGO -->
            <div class="px-6 py-6 border-b border-white/10 flex items-center gap-3">
                <ApplicationLogo class="h-10 w-10 text-white" />

                <div>
                    <h1 class="text-sm font-semibold leading-tight">
                        IKK GROUP
                    </h1>
                    <p class="text-xs text-gray-300">
                        Properti & Development
                    </p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="flex-1 mt-4">
                <Link
                    v-for="menu in menus"
                    :key="menu.name"
                    :href="menu.href"
                    class="flex items-center px-6 py-3 text-sm transition-all duration-200"
                    :class="[
                        isActive(menu.href)
                            ? 'bg-[#C9A84C] text-[#0B1F4A] font-semibold'
                            : 'text-gray-300 hover:bg-[#C9A84C] hover:text-[#0B1F4A]'
                    ]"
                >
                    <span class="mr-3 text-base">
                        {{ menu.icon }}
                    </span>
                    {{ menu.name }}
                </Link>
            </nav>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t border-white/10 text-xs text-gray-400">
                © 2026 IKK Properti
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <!-- TOP NAVBAR -->
            <header class="bg-white px-8 py-4 flex justify-between items-center shadow-sm">

                <div>
                    <h2 class="text-xl font-semibold text-[#0B1F4A]">
                        Dashboard
                    </h2>
                    <p class="text-xs text-gray-500">
                        Sistem Manajemen Properti
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700">
                        {{ $page.props.auth?.user?.name || 'Admin' }}
                    </span>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-[#C9A84C] hover:underline"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="p-8">
                <slot />
            </main>

        </div>

    </div>
</template>
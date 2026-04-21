<script setup>
import { ref } from 'vue'

const menus = [
    { name: 'Dashboard', icon: '🏠' },
    { name: 'Katalog Properti', icon: '🏘️' },
    { name: 'Kategori', icon: '📂' },
    { name: 'Booking Konsultasi', icon: '📅' },
    { name: 'Data Klien', icon: '👥' },
]
</script>

<template>
    <div class="flex min-h-screen bg-[#F8F8F8]">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-[#0B1F4A] text-white flex flex-col">

            <!-- LOGO -->
            <div class="px-6 py-6 border-b border-white/10">
                <div class="leading-tight">
                    <h1 class="text-lg font-semibold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">
                        IKK GROUP
                    </h1>
                    <p class="text-xs text-gray-300">
                        Properti & Development
                    </p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="flex-1 mt-4">
                <a
                    v-for="menu in menus"
                    :key="menu.name"
                    href="#"
                    class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-[#C9A84C] hover:text-[#0B1F4A] transition-all duration-200"
                >
                    <span class="mr-3 text-base">
                        {{ menu.icon }}
                    </span>
                    {{ menu.name }}
                </a>
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
                    <h2 class="text-xl font-semibold text-[#0B1F4A]" style="font-family: 'Cormorant Garamond', serif;">
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

                    <button class="text-sm text-[#C9A84C] hover:underline">
                        Logout
                    </button>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="p-8">
                <slot />
            </main>
        </div>

    </div>
</template>

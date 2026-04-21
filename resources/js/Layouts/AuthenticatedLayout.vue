<script setup>
import { ref } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

const sidebarOpen = ref(true)

// MENU USER (CALON KLIEN)
const userMenus = [
    { name: 'Katalog Properti', icon: '🏘️', href: '#' },
    { name: 'Cari Properti', icon: '🔍', href: '#' },
    { name: 'Booking Konsultasi', icon: '📅', href: '#' },
]

// MENU ADMIN / MARKETING
const adminMenus = [
    { name: 'Kelola Properti', icon: '🏗️', href: '#' },
    { name: 'Kategori Properti', icon: '📂', href: '#' },
    { name: 'Jadwal Konsultasi', icon: '🗓️', href: '#' },
    { name: 'Data Klien', icon: '👥', href: '#' },
]

// SIMULASI ROLE (nanti bisa dari auth)
const role = 'admin' // ganti 'user' kalau mau test user view
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <ApplicationLogo class="h-10" />
            </div>

            <nav class="mt-4">
                <!-- USER MENU -->
                <div v-if="role === 'user'">
                    <a
                        v-for="menu in userMenus"
                        :key="menu.name"
                        :href="menu.href"
                        class="flex items-center px-4 py-3 hover:bg-gray-100 transition"
                    >
                        <span class="mr-3">{{ menu.icon }}</span>
                        {{ menu.name }}
                    </a>
                </div>

                <!-- ADMIN MENU -->
                <div v-if="role === 'admin'">
                    <a
                        v-for="menu in adminMenus"
                        :key="menu.name"
                        :href="menu.href"
                        class="flex items-center px-4 py-3 hover:bg-gray-100 transition"
                    >
                        <span class="mr-3">{{ menu.icon }}</span>
                        {{ menu.name }}
                    </a>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">

            <!-- TOPBAR -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-lg font-semibold">
                    Dashboard Properti
                </h1>

                <div class="flex items-center gap-4">
                    <span class="text-gray-700">
                        {{ $page.props.auth.user.name }}
                    </span>

                    <button class="text-red-500 hover:underline">
                        Logout
                    </button>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

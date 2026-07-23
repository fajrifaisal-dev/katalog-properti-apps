<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

const page = usePage()

// TOAST FLASH MESSAGE
const toast = ref(null) // { type: 'success' | 'error', message: string }

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            toast.value = { type: 'success', message: flash.success }
        } else if (flash?.error) {
            toast.value = { type: 'error', message: flash.error }
        } else {
            return
        }

        setTimeout(() => { toast.value = null }, 3000)
    },
    { deep: true, immediate: true }
)

// MOBILE SIDEBAR TOGGLE
const sidebarOpen = ref(false)

const closeSidebar = () => {
    sidebarOpen.value = false
}

// tutup otomatis kalau pindah halaman
watch(
    () => page.url,
    () => { sidebarOpen.value = false }
)

// MENU ADMIN
const menus = [
    { name: 'Dashboard', icon: '🏠', href: route('dashboard') },
    { name: 'Kategori', icon: '📂', href: route('categories.index') },
    { name: 'Katalog Properti', icon: '🏘️', href: route('properties.index') },
    { name: 'Booking Konsultasi', icon: '📅', href: route('admin.bookings.index') },
    { name: 'Data Klien', icon: '👥', href: route('admin.clients.index') },
]

// helper untuk active menu
const isActive = (href) => {
    return page.url.startsWith(new URL(href, window.location.origin).pathname)
}
</script>

<template>
    <div class="flex min-h-screen bg-[#F8F8F8]">
        <div
            v-if="toast"
            class="fixed top-5 right-5 z-[9999] px-4 py-3 rounded-lg shadow-lg text-sm text-white transition-all"
            :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
        >
            {{ toast.message }}
        </div>

        <!-- OVERLAY (mobile only, muncul saat sidebar open) -->
        <div
            v-if="sidebarOpen"
            @click="closeSidebar"
            class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        ></div>

        <!-- SIDEBAR -->
        <aside
            class="w-64 bg-[#0B1F4A] text-white flex flex-col fixed inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >

            <!-- LOGO -->
            <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
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

                <!-- tombol close, mobile only -->
                <button
                    @click="closeSidebar"
                    class="lg:hidden text-gray-300 hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- MENU -->
            <nav class="flex-1 mt-4 overflow-y-auto">
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
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOP NAVBAR -->
            <header class="bg-white px-4 sm:px-8 py-4 flex justify-between items-center shadow-sm">

                <div class="flex items-center gap-3">
                    <!-- tombol hamburger, mobile only -->
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden text-[#0B1F4A]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-[#0B1F4A]">
                            Dashboard
                        </h2>
                        <p class="text-xs text-gray-500 hidden sm:block">
                            Sistem Manajemen Properti
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700 hidden sm:inline">
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
            <main class="p-4 sm:p-8">
                <slot />
            </main>

        </div>

    </div>
</template>
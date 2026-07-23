<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    stats: Object,
    bookingsByStatus: Object,
    recentBookings: Array,
    recentProperties: Array,
    bookingTrend: Array,
    filters: Object,
})

const form = reactive({
    status: props.filters?.status ?? '',
    date_from: props.filters?.date_from ?? '',
    date_to: props.filters?.date_to ?? '',
})

const applyFilters = () => {
    router.get(route('dashboard'), {
        status: form.status || undefined,
        date_from: form.date_from || undefined,
        date_to: form.date_to || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

const resetFilters = () => {
    form.status = ''
    form.date_from = ''
    form.date_to = ''
    applyFilters()
}

const formatRupiah = (value) => {
    if (!value) return 'Rp 0'
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const statusBadgeClass = (status) => {
    const map = {
        'Menunggu': 'bg-yellow-100 text-yellow-800',
        'Disetujui': 'bg-green-100 text-green-800',
        'Ditolak': 'bg-red-100 text-red-800',
    }
    return map[status] ?? 'bg-gray-100 text-gray-800'
}

const cards = computed(() => [
    { label: 'Total Properti', value: props.stats.totalProperties },
    { label: 'Total Kategori', value: props.stats.totalCategories },
    { label: 'Total Client', value: props.stats.totalClients },
    { label: 'Total Booking', value: props.stats.totalBookings },
])
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
            <h1 class="text-xl font-bold">
                Dashboard
            </h1>

            <!-- Filter Panel -->
            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="font-semibold mb-3">Filter</h2>
                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status</label>
                        <select
                            v-model="form.status"
                            class="border rounded-lg px-3 py-2 text-sm"
                        >
                            <option value="">Semua Status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Dari Tanggal</label>
                        <input
                            v-model="form.date_from"
                            type="date"
                            class="border rounded-lg px-3 py-2 text-sm"
                        />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Sampai Tanggal</label>
                        <input
                            v-model="form.date_to"
                            type="date"
                            class="border rounded-lg px-3 py-2 text-sm"
                        />
                    </div>

                    <div class="flex gap-2">
                        <button
                            @click="applyFilters"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700"
                        >
                            Terapkan
                        </button>
                        <button
                            @click="resetFilters"
                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="card in cards"
                    :key="card.label"
                    class="bg-white rounded-xl shadow p-4"
                >
                    <p class="text-sm text-gray-500">{{ card.label }}</p>
                    <p class="text-2xl font-bold">{{ card.value }}</p>
                </div>
            </div>

            <!-- Booking by Status -->
            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="font-semibold mb-3">Booking per Status</h2>
                <div class="flex gap-3 flex-wrap">
                    <span
                        v-for="(total, status) in bookingsByStatus"
                        :key="status"
                        :class="statusBadgeClass(status)"
                        class="px-3 py-1 rounded-full text-sm font-medium"
                    >
                        {{ status }}: {{ total }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow p-4">
                    <h2 class="font-semibold mb-3">Daftar Booking</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2">No. Booking</th>
                                <th class="py-2">Client</th>
                                <th class="py-2">Properti</th>
                                <th class="py-2">Tanggal</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="booking in recentBookings"
                                :key="booking.id"
                                class="border-b last:border-0"
                            >
                                <td class="py-2">{{ booking.nomor_booking }}</td>
                                <td class="py-2">{{ booking.client_nama }}</td>
                                <td class="py-2">{{ booking.properti_nama }}</td>
                                <td class="py-2">
                                    {{ booking.tanggal_konsultasi }}
                                    <span v-if="booking.jam_konsultasi">{{ booking.jam_konsultasi }}</span>
                                </td>
                                <td class="py-2">
                                    <span
                                        :class="statusBadgeClass(booking.status)"
                                        class="px-2 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!recentBookings.length">
                                <td colspan="5" class="py-4 text-center text-gray-400">
                                    Tidak ada booking untuk filter ini
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Properties -->
                <div class="bg-white rounded-xl shadow p-4">
                    <h2 class="font-semibold mb-3">Properti Terbaru</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2">Nama</th>
                                <th class="py-2">Kategori</th>
                                <th class="py-2">Lokasi</th>
                                <th class="py-2">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="property in recentProperties"
                                :key="property.id"
                                class="border-b last:border-0"
                            >
                                <td class="py-2">{{ property.nama_properti }}</td>
                                <td class="py-2">{{ property.kategori }}</td>
                                <td class="py-2">{{ property.lokasi }}</td>
                                <td class="py-2">{{ formatRupiah(property.harga) }}</td>
                            </tr>
                            <tr v-if="!recentProperties.length">
                                <td colspan="4" class="py-4 text-center text-gray-400">
                                    Belum ada properti
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
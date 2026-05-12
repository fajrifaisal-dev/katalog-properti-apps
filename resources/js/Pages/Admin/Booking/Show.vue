<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'


const props = defineProps({
    booking: Object,
    statusList: Array,
})

const page = usePage()
const flash = page.props.flash ?? {}

const selectedStatus = ref(props.booking.status)
const isUpdating = ref(false)

const statusColor = (s) => {
    const map = {
        Menunggu:  'bg-yellow-100 text-yellow-700 border-yellow-200',
        Disetujui: 'bg-blue-100 text-blue-700 border-blue-200',
        Ditolak:   'bg-red-100 text-red-700 border-red-200',
    }
    return map[s] ?? 'bg-gray-100 text-gray-600 border-gray-200'
}

const statusLabel = (s) => {
    const map = {
        Menunggu:  'Menunggu',
        Disetujui: 'Disetujui',
        Ditolak:   'Ditolak',
    }
    return map[s] ?? s
}

const formatTanggal = (val) => {
    if (!val) return '-'
    return new Date(val).toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    })
}

const updateStatus = () => {
    isUpdating.value = true
    router.patch(
        route('admin.bookings.updateStatus', props.booking.id),
        { status: selectedStatus.value },
        {
            onFinish: () => { isUpdating.value = false }
        }
    )
}

const confirmDelete = () => {
    if (confirm('Yakin ingin menghapus booking ini? Tindakan ini tidak bisa dibatalkan.')) {
        router.delete(route('admin.bookings.destroy', props.booking.id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6 max-w-4xl">

            <!-- Back + Header -->
            <div class="flex items-start justify-between">
                <div>
                    <Link
                        :href="route('admin.bookings.index')"
                        class="text-sm text-gray-500 hover:text-[#0B1F4A] flex items-center gap-1 mb-2 transition-colors"
                    >
                        ← Kembali ke Daftar Booking
                    </Link>
                    <h1 class="text-2xl font-bold text-[#0B1F4A]">Detail Booking</h1>
                    <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ booking.nomor_booking }}</p>
                </div>

                <!-- Status badge -->
                <span
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border"
                    :class="statusColor(booking.status)"
                >
                    {{ statusLabel(booking.status) }}
                </span>
            </div>

            <!-- Flash -->
            <div v-if="flash.success"
                class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                ✓ {{ flash.success }}
            </div>

            <!-- Grid: Info + Update Status -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- Kiri: Info Booking -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Jadwal Konsultasi -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 bg-[#0B1F4A] text-white text-sm font-semibold">
                            📅 Jadwal Konsultasi
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Tanggal</p>
                                <p class="text-sm font-medium text-[#0B1F4A]">
                                    {{ formatTanggal(booking.tanggal_konsultasi) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Jam</p>
                                <p class="text-sm font-medium text-[#0B1F4A]">{{ booking.jam_konsultasi }} WIB</p>
                            </div>
                            <div v-if="booking.catatan" class="col-span-2">
                                <p class="text-xs text-gray-400 mb-1">Catatan</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ booking.catatan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Klien -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 bg-[#0B1F4A] text-white text-sm font-semibold">
                            👤 Data Klien
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Nama</p>
                                <p class="text-sm font-medium text-[#0B1F4A]">{{ booking.client?.nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">No. HP</p>
                                <p class="text-sm font-medium text-[#0B1F4A]">{{ booking.client?.no_hp ?? '-' }}</p>
                            </div>
                            <div v-if="booking.client?.email">
                                <p class="text-xs text-gray-400 mb-1">Email</p>
                                <p class="text-sm text-gray-700">{{ booking.client.email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Properti -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 bg-[#0B1F4A] text-white text-sm font-semibold">
                            🏘️ Properti yang Diminati
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Nama Properti</p>
                                <p class="text-sm font-medium text-[#0B1F4A]">{{ booking.property?.nama_properti ?? '-' }}</p>
                            </div>
                            <div v-if="booking.property?.kategori">
                                <p class="text-xs text-gray-400 mb-1">Kategori</p>
                                <p class="text-sm text-gray-700">{{ booking.property.kategori.nama_kategori }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kanan: Update Status + Meta -->
                <div class="space-y-5">

                    <!-- Update Status -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 bg-[#C9A84C] text-[#0B1F4A] text-sm font-semibold">
                            🔄 Ubah Status
                        </div>
                        <div class="p-5 space-y-3">
                            <div
                                v-for="s in statusList"
                                :key="s"
                                @click="selectedStatus = s"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg border cursor-pointer transition-all"
                                :class="[
                                    selectedStatus === s
                                        ? 'border-[#C9A84C] bg-[#C9A84C]/10'
                                        : 'border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <div
                                    class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                                    :class="selectedStatus === s ? 'border-[#C9A84C]' : 'border-gray-300'"
                                >
                                    <div
                                        v-if="selectedStatus === s"
                                        class="w-2 h-2 rounded-full bg-[#C9A84C]"
                                    />
                                </div>
                                <span class="text-sm" :class="selectedStatus === s ? 'font-semibold text-[#0B1F4A]' : 'text-gray-600'">
                                    {{ statusLabel(s) }}
                                </span>
                            </div>

                            <button
                                @click="updateStatus"
                                :disabled="isUpdating || selectedStatus === booking.status"
                                class="w-full mt-2 py-2.5 rounded-lg text-sm font-semibold transition-all"
                                :class="[
                                    (isUpdating || selectedStatus === booking.status)
                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                        : 'bg-[#0B1F4A] text-white hover:bg-[#0d2660]'
                                ]"
                            >
                                {{ isUpdating ? 'Menyimpan...' : 'Simpan Status' }}
                            </button>
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Informasi</h3>
                        <div>
                            <p class="text-xs text-gray-400">Dibuat pada</p>
                            <p class="text-sm text-gray-700">{{ formatTanggal(booking.created_at) }}</p>
                        </div>
                        <div v-if="booking.user">
                            <p class="text-xs text-gray-400">Diproses oleh</p>
                            <p class="text-sm text-gray-700">{{ booking.user.name }}</p>
                        </div>
                    </div>

                    <!-- Hapus -->
                    <button
                        @click="confirmDelete"
                        class="w-full py-2.5 rounded-xl text-sm font-medium text-red-600 border border-red-200 hover:bg-red-50 transition-colors"
                    >
                        🗑️ Hapus Booking Ini
                    </button>

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
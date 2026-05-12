<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link, router, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'


const props = defineProps({
    bookings: Object,
    filters: Object,
    statusList: Array,
})

const search = ref(props.filters?.search ?? '')
const status = ref(props.filters?.status ?? '')
const tanggal = ref(props.filters?.tanggal ?? '')

// Debounce search
let timeout = null
watch([search, status, tanggal], () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        router.get(route('admin.bookings.index'), {
            search: search.value,
            status: status.value,
            tanggal: tanggal.value,
        }, { preserveState: true, replace: true })
    }, 400)
})

const statusColor = (s) => {
    const map = {
        Menunggu:  'bg-yellow-100 text-yellow-700',
        Disetujui: 'bg-blue-100 text-blue-700',
        Ditolak:   'bg-red-100 text-red-700',
    }
    return map[s] ?? 'bg-gray-100 text-gray-600'
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
        day: 'numeric', month: 'long', year: 'numeric'
    })
}

const page = usePage()
const flash = page.props.flash ?? {}

const confirmDelete = (id) => {
    if (confirm('Yakin ingin menghapus booking ini?')) {
        router.delete(route('admin.bookings.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#0B1F4A]">Booking Konsultasi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola semua jadwal konsultasi properti</p>
                </div>
                <div class="text-sm text-gray-500 bg-white border border-gray-200 rounded-lg px-4 py-2">
                    Total: <span class="font-semibold text-[#0B1F4A]">{{ bookings.total }}</span> booking
                </div>
            </div>

            <!-- Flash -->
            <div v-if="flash.success"
                class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                {{ flash.success }}
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3">
                <!-- Search -->
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nomor booking atau nama..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                    />
                </div>

                <!-- Status filter -->
                <select
                    v-model="status"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C] bg-white"
                >
                    <option value="">Semua Status</option>
                    <option v-for="s in statusList" :key="s" :value="s">{{ statusLabel(s) }}</option>
                </select>

                <!-- Tanggal filter -->
                <input
                    v-model="tanggal"
                    type="date"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#0B1F4A] text-white text-left">
                            <th class="px-5 py-3.5 font-medium">No. Booking</th>
                            <th class="px-5 py-3.5 font-medium">Klien</th>
                            <th class="px-5 py-3.5 font-medium">Properti</th>
                            <th class="px-5 py-3.5 font-medium">Tanggal & Jam</th>
                            <th class="px-5 py-3.5 font-medium">Status</th>
                            <th class="px-5 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="bookings.data.length === 0">
                            <td colspan="6" class="text-center py-16 text-gray-400">
                                <div class="text-4xl mb-2">📅</div>
                                <p>Tidak ada data booking</p>
                            </td>
                        </tr>
                        <tr
                            v-for="b in bookings.data"
                            :key="b.id"
                            class="hover:bg-[#F8F8F8] transition-colors"
                        >
                            <!-- No Booking -->
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    {{ b.nomor_booking }}
                                </span>
                            </td>

                            <!-- Client -->
                            <td class="px-5 py-4">
                                <div class="font-medium text-[#0B1F4A]">
                                    {{ b.client?.nama ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ b.client?.no_hp ?? '' }}
                                </div>
                            </td>

                            <!-- Properti -->
                            <td class="px-5 py-4 text-gray-600 max-w-[160px] truncate">
                                {{ b.property?.nama_properti ?? '-' }}
                            </td>

                            <!-- Tanggal Jam -->
                            <td class="px-5 py-4">
                                <div class="text-[#0B1F4A] font-medium">{{ formatTanggal(b.tanggal_konsultasi) }}</div>
                                <div class="text-xs text-gray-400">{{ b.jam_konsultasi }}</div>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                    :class="statusColor(b.status)"
                                >
                                    {{ statusLabel(b.status) }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('admin.bookings.show', b.id)"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-[#0B1F4A] text-white hover:bg-[#0d2660] transition-colors"
                                    >
                                        Detail
                                    </Link>
                                    <button
                                        @click="confirmDelete(b.id)"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="bookings.last_page > 1" class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ bookings.from }}–{{ bookings.to }} dari {{ bookings.total }} data
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in bookings.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="px-3 py-1.5 text-sm rounded-lg border transition-colors"
                        :class="[
                            link.active
                                ? 'bg-[#C9A84C] border-[#C9A84C] text-white font-semibold'
                                : link.url
                                    ? 'bg-white border-gray-200 text-gray-600 hover:border-[#C9A84C] hover:text-[#C9A84C]'
                                    : 'bg-white border-gray-100 text-gray-300 cursor-not-allowed'
                        ]"
                        :preserve-scroll="true"
                    />
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
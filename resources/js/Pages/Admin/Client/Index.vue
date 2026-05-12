<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
    clients: Object,
    filters: Object,
})

const page = usePage()
const flash = page.props.flash ?? {}
const errors = page.props.errors ?? {}

const search = ref(props.filters?.search ?? '')

let timeout = null
watch(search, () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        router.get(route('admin.clients.index'), {
            search: search.value,
        }, { preserveState: true, replace: true })
    }, 400)
})

const confirmDelete = (id) => {
    if (confirm('Yakin ingin menghapus client ini? Pastikan semua booking sudah ditolak.')) {
        router.delete(route('admin.clients.destroy', id), {
            onError: () => {}
        })
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#0B1F4A]">Data Client</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola semua data klien yang pernah booking</p>
                </div>
                <div class="text-sm text-gray-500 bg-white border border-gray-200 rounded-lg px-4 py-2">
                    Total: <span class="font-semibold text-[#0B1F4A]">{{ clients.total }}</span> client
                </div>
            </div>

            <!-- Flash -->
            <div v-if="flash.success"
                class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                ✓ {{ flash.success }}
            </div>

            <!-- Error hapus -->
            <div v-if="errors.delete"
                class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                ⚠ {{ errors.delete }}
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="relative max-w-sm">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama, email, atau no. HP..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#0B1F4A] text-white text-left">
                            <th class="px-5 py-3.5 font-medium">Nama</th>
                            <th class="px-5 py-3.5 font-medium">No. HP</th>
                            <th class="px-5 py-3.5 font-medium">Email</th>
                            <th class="px-5 py-3.5 font-medium text-center">Jumlah Booking</th>
                            <th class="px-5 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="clients.data.length === 0">
                            <td colspan="5" class="text-center py-16 text-gray-400">
                                <div class="text-4xl mb-2">👤</div>
                                <p>Tidak ada data client</p>
                            </td>
                        </tr>
                        <tr
                            v-for="c in clients.data"
                            :key="c.id"
                            class="hover:bg-[#F8F8F8] transition-colors"
                        >
                            <td class="px-5 py-4 font-medium text-[#0B1F4A]">{{ c.nama }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ c.no_hp }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ c.email ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#0B1F4A]/10 text-[#0B1F4A] text-xs font-semibold">
                                    {{ c.bookings_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('admin.clients.edit', c.id)"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-[#0B1F4A] text-white hover:bg-[#0d2660] transition-colors"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="confirmDelete(c.id)"
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
            <div v-if="clients.last_page > 1" class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ clients.from }}–{{ clients.to }} dari {{ clients.total }} data
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in clients.links"
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
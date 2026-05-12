<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage, Head } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({
    nomor_booking: ''
})

const errorMsg = ref(null)

// ✅ langsung ambil dari root props
const booking = computed(() => page.props.booking || null)
const found = computed(() => page.props.found)
const message = computed(() => page.props.message)

const submit = () => {
    errorMsg.value = null

    form.post('/cek-booking', {
        preserveScroll: true,
        onError: () => {
            errorMsg.value = 'Terjadi kesalahan. Coba lagi.'
        }
    })
}

const formatDate = (dateStr) => {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

const formatTime = (timeStr) => {
    if (!timeStr) return '-'
    return timeStr.slice(0, 5) + ' WIB'
}

const statusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'menunggu': return 'bg-amber-400 text-[#0B1F4A]'
        case 'dikonfirmasi': return 'bg-green-500 text-white'
        case 'dibatalkan': return 'bg-red-500 text-white'
        case 'selesai': return 'bg-blue-500 text-white'
        default: return 'bg-gray-400 text-white'
    }
}
</script>

<template>

    <Head title="Cek Status Booking" />

    <div class="min-h-screen bg-[#F4F6FA]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-[#0B1F4A]">
                    Cek Status Booking
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Masukkan nomor booking Anda
                </p>
            </div>

            <!-- FORM -->
            <div class="bg-white rounded-xl shadow-md p-5 sm:p-6 mb-6">
                <label class="text-sm font-medium text-gray-600">
                    Nomor Booking
                </label>

                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                    <input v-model="form.nomor_booking" type="text" placeholder="IKK-20260501-4640"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#0B1F4A]/30 focus:outline-none"
                        @keyup.enter="submit" />

                    <button @click="submit" :disabled="form.processing || !form.nomor_booking"
                        class="bg-[#0B1F4A] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#162d6b] transition disabled:opacity-50">
                        <span v-if="form.processing">...</span>
                        <span v-else>Cek</span>
                    </button>
                </div>

                <p v-if="form.errors.nomor_booking" class="text-red-500 text-xs mt-2">
                    {{ form.errors.nomor_booking }}
                </p>
            </div>

            <!-- ERROR -->
            <div v-if="errorMsg" class="bg-red-100 text-red-600 p-4 rounded-lg mb-6 text-sm">
                {{ errorMsg }}
            </div>

            <!-- NOT FOUND -->
            <div v-if="!found && form.nomor_booking" class="bg-red-100 text-red-600 p-4 rounded-lg mb-6 text-sm">
                {{ message }}
            </div>

            <!-- RESULT -->
            <div v-if="booking" class="space-y-5">

                <div class="bg-white rounded-xl shadow-md overflow-hidden">

                    <!-- HEADER -->
                    <div class="bg-[#0B1F4A] text-white p-5 flex justify-between items-start">
                        <div>
                            <p class="text-xs text-[#C9A84C] uppercase tracking-wide">
                                Kode Booking
                            </p>
                            <p class="text-lg font-bold">
                                {{ booking.nomor_booking }}
                            </p>
                        </div>

                        <span :class="statusColor(booking.status)" class="text-xs font-bold px-3 py-1 rounded-full">
                            {{ booking.status }}
                        </span>
                    </div>

                    <!-- CONTENT -->
                    <div class="p-5 space-y-4">

                        <!-- GRID -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="card">
                                <p class="label">Nama</p>
                                <p class="value">{{ booking.client?.nama || '-' }}</p>
                            </div>

                            <div class="card">
                                <p class="label">No HP</p>
                                <p class="value">{{ booking.client?.no_hp || '-' }}</p>
                            </div>

                            <div class="card">
                                <p class="label">Tanggal</p>
                                <p class="value">{{ formatDate(booking.tanggal_konsultasi) }}</p>
                            </div>

                            <div class="card">
                                <p class="label">Jam</p>
                                <p class="value">{{ formatTime(booking.jam_konsultasi) }}</p>
                            </div>

                        </div>

                        <!-- PROPERTI -->
                        <!-- PROPERTI -->
                        <div class="card space-y-3">

                            <p class="label">Properti</p>

                            <!-- GAMBAR -->
                            <div v-if="booking.property?.gambar" class="w-full">
                                <img :src="`/storage/${booking.property.gambar}`"
                                    class="w-full h-48 object-cover rounded-lg" />
                            </div>

                            <!-- NAMA -->
                            <p class="value text-base">
                                {{ booking.property?.nama_properti || '-' }}
                            </p>

                            <!-- KATEGORI -->
                            <p class="text-sm text-gray-500">
                                {{ booking.property?.kategori?.nama_kategori }}
                            </p>

                            <!-- LOKASI -->
                            <div>
                                <p class="label">Lokasi</p>
                                <p class="text-sm text-gray-700">
                                    {{ booking.property?.lokasi || '-' }}
                                </p>
                            </div>

                            <!-- HARGA -->
                            <div>
                                <p class="label">Harga</p>
                                <p class="text-sm font-semibold text-green-600">
                                    Rp {{ Number(booking.property?.harga || 0).toLocaleString('id-ID') }}
                                </p>
                            </div>

                            <!-- DESKRIPSI -->
                            <div v-if="booking.property?.deskripsi">
                                <p class="label">Deskripsi</p>
                                <p class="text-sm text-gray-700">
                                    {{ booking.property.deskripsi }}
                                </p>
                            </div>

                        </div>

                        <!-- CATATAN -->
                        <div v-if="booking.catatan" class="card">
                            <p class="label">Catatan</p>
                            <p class="text-sm text-gray-700">{{ booking.catatan }}</p>
                        </div>

                        <!-- INFO -->
                        <div class="bg-yellow-100 text-yellow-700 p-4 rounded-lg text-sm">
                            Tim kami akan menghubungi Anda untuk konfirmasi.
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<style>
.card {
    @apply bg-white rounded-xl p-4 shadow-sm border border-gray-100;
}

.label {
    @apply text-xs text-gray-400 mb-1;
}

.value {
    @apply text-sm font-semibold text-[#0B1F4A];
}
</style>
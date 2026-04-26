<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()

const props = defineProps({
    properties: Object,
    kategori: Array
})

// ==============================
// STATE MODAL
// ==============================
const showTambahModal = ref(false)
const showEditModal = ref(false)
const showHapusModal = ref(false)
const selectedItem = ref(null)
const previewTambah = ref(null)
const previewEdit = ref(null)

// ==============================
// FORMAT RUPIAH
// ==============================
const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(val)
}

// 🔥 FORMAT INPUT
const formatRupiahInput = (value) => {
    if (!value) return ''
    return new Intl.NumberFormat('id-ID').format(value)
}

const parseRupiah = (value) => {
    return value.replace(/\D/g, '')
}

// ==============================
// FORM TAMBAH
// ==============================
const formTambah = useForm({
    kategori_id: '',
    nama_properti: '',
    lokasi: '',
    harga: '',
    deskripsi: '',
    gambar: null,
})

const onGambarTambah = (e) => {
    const file = e.target.files[0]
    formTambah.gambar = file
    previewTambah.value = file ? URL.createObjectURL(file) : null
}

const submitTambah = () => {
    formTambah.post(route('properties.store'), {
        forceFormData: true,
        onSuccess: () => {
            formTambah.reset()
            previewTambah.value = null
            showTambahModal.value = false
        }
    })
}

// ==============================
// FORM EDIT
// ==============================
const formEdit = useForm({
    kategori_id: '',
    nama_properti: '',
    lokasi: '',
    harga: '',
    deskripsi: '',
    gambar: null,
})

const openEdit = (item) => {
    selectedItem.value = item
    formEdit.kategori_id = item.kategori_id
    formEdit.nama_properti = item.nama_properti
    formEdit.lokasi = item.lokasi
    formEdit.harga = item.harga
    formEdit.deskripsi = item.deskripsi || ''
    formEdit.gambar = null
    previewEdit.value = item.gambar ? `/storage/${item.gambar}` : null
    showEditModal.value = true
}

const onGambarEdit = (e) => {
    const file = e.target.files[0]
    formEdit.gambar = file
    previewEdit.value = file ? URL.createObjectURL(file) : null
}

const submitEdit = () => {
    formEdit.post(route('properties.update', selectedItem.value.id), {
        forceFormData: true,
        headers: { 'X-HTTP-Method-Override': 'PUT' },
        onSuccess: () => {
            showEditModal.value = false
            selectedItem.value = null
            previewEdit.value = null
        }
    })
}

// ==============================
// HAPUS
// ==============================
const openHapus = (item) => {
    selectedItem.value = item
    showHapusModal.value = true
}

const confirmHapus = () => {
    router.delete(route('properties.destroy', selectedItem.value.id), {
        preserveScroll: true,
        onStart: () => console.log('Deleting...'),
        onSuccess: () => {
            showHapusModal.value = false
            selectedItem.value = null
        }
    })
}


// ==============================
// PAGINATION
// ==============================
const goToPage = (url) => {
    if (url) router.visit(url, { preserveState: true })
}
</script>

<template>
    <AuthenticatedLayout>

        <!-- HEADER -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Manajemen Properti</h1>
                <p class="text-sm text-gray-500">Kelola data properti yang tersedia</p>
            </div>
            <button @click="showTambahModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Properti
            </button>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-[1100px] text-sm border-collapse">

                    <!-- HEADER -->
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left whitespace-nowrap">Gambar</th>
                            <th class="p-3 text-left whitespace-nowrap">Nama</th>
                            <th class="p-3 text-left whitespace-nowrap">Kategori</th>
                            <th class="p-3 text-left whitespace-nowrap">Lokasi</th>
                            <th class="p-3 text-right whitespace-nowrap">Harga</th>
                            <th class="p-3 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody>
                        <tr v-for="item in props.properties.data" :key="item.id" class="border-b hover:bg-gray-50">
                            <!-- GAMBAR -->
                            <td class="p-3 whitespace-nowrap">
                                <img v-if="item.gambar" :src="`/storage/${item.gambar}`"
                                    class="w-16 h-12 object-cover rounded" />
                                <div v-else
                                    class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">
                                    No img
                                </div>
                            </td>

                            <!-- NAMA -->
                            <td class="p-3 whitespace-nowrap font-medium">
                                {{ item.nama_properti }}
                            </td>

                            <!-- KATEGORI -->
                            <td class="p-3 whitespace-nowrap text-gray-600">
                                {{ item.kategori?.nama_kategori || '-' }}
                            </td>

                            <!-- 🔥 LOKASI (GOOGLE MAP + HANDLE TEXT PANJANG) -->
                            <td class="p-3 text-gray-600 max-w-[300px] break-words">
                                <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.lokasi)}`"
                                    target="_blank" class="text-blue-600 hover:underline">
                                    📍 {{ item.lokasi }}
                                </a>
                            </td>

                            <!-- HARGA -->
                            <td class="p-3 text-right font-semibold whitespace-nowrap">
                                {{ formatRupiah(item.harga) }}
                            </td>

                            <!-- AKSI -->
                            <td class="p-3 text-center whitespace-nowrap space-x-2">
                                <button @click="openEdit(item)"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:opacity-90">
                                    Edit
                                </button>
                                <button @click="openHapus(item)"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:opacity-90">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <!-- EMPTY STATE -->
                        <tr v-if="props.properties.data.length === 0">
                            <td colspan="6" class="text-center p-4 text-gray-500">
                                Belum ada data properti
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>


        <!-- =========================
         MODAL TAMBAH
    ========================= -->
        <div v-if="showTambahModal" class="fixed inset-0 flex items-center justify-center bg-black/40">
            <div class="bg-white p-6 rounded-xl w-full max-w-lg">

                <form @submit.prevent="submitTambah" class="space-y-3">

                    <!-- NAMA -->
                    <input v-model="formTambah.nama_properti" placeholder="Nama Properti"
                        class="w-full border p-2 rounded" />

                    <!-- LOKASI -->
                    <input v-model="formTambah.lokasi" placeholder="Lokasi" class="w-full border p-2 rounded" />

                    <!-- 🔥 HARGA FORMAT -->
                    <input :value="formatRupiahInput(formTambah.harga)"
                        @input="formTambah.harga = parseRupiah($event.target.value)" placeholder="Harga"
                        class="w-full border p-2 rounded" />

                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <!-- =========================
         MODAL EDIT
    ========================= -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

            <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] flex flex-col">

                <!-- HEADER -->
                <div class="p-4 border-b font-semibold">
                    Edit Properti
                </div>

                <!-- CONTENT (SCROLLABLE) -->
                <div class="p-4 overflow-y-auto space-y-3">

                    <form @submit.prevent="submitEdit" class="space-y-3">

                        <select v-model="formEdit.kategori_id" class="w-full border p-2 rounded">
                            <option value="">Pilih Kategori</option>
                            <option v-for="kat in props.kategori" :key="kat.id" :value="kat.id">
                                {{ kat.nama_kategori }}
                            </option>
                        </select>

                        <input v-model="formEdit.nama_properti" placeholder="Nama Properti"
                            class="w-full border p-2 rounded" />

                        <input v-model="formEdit.lokasi" placeholder="Lokasi" class="w-full border p-2 rounded" />

                        <input :value="formatRupiahInput(formEdit.harga)"
                            @input="formEdit.harga = parseRupiah($event.target.value)" placeholder="Harga"
                            class="w-full border p-2 rounded" />

                        <textarea v-model="formEdit.deskripsi" placeholder="Deskripsi"
                            class="w-full border p-2 rounded"></textarea>

                        <input type="file" @change="onGambarEdit" />

                        <img v-if="previewEdit" :src="previewEdit" class="w-full h-40 object-cover rounded mt-2" />

                    </form>

                </div>

                <!-- FOOTER (STICKY) -->
                <div class="p-4 border-t flex justify-end gap-2">
                    <button type="button" @click="showEditModal = false">
                        Batal
                    </button>

                    <button @click="submitEdit" class="bg-yellow-500 text-white px-4 py-2 rounded">
                        Update
                    </button>
                </div>

            </div>
        </div>


        <!-- =========================
     MODAL HAPUS
========================= -->
        <div v-if="showHapusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

            <div class="bg-white rounded-xl w-full max-w-md p-6">

                <!-- TITLE -->
                <h2 class="text-lg font-semibold mb-2 text-gray-800">
                    Hapus Properti
                </h2>

                <!-- MESSAGE -->
                <p class="text-sm text-gray-600 mb-4">
                    Yakin ingin menghapus properti
                    <span class="font-semibold">
                        "{{ selectedItem?.nama_properti }}"
                    </span> ?
                </p>

                <!-- OPTIONAL PREVIEW -->
                <img v-if="selectedItem?.gambar" :src="`/storage/${selectedItem.gambar}`"
                    class="w-full h-32 object-cover rounded mb-4" />

                <!-- ACTION -->
                <div class="flex justify-end gap-2">
                    <button @click="showHapusModal = false" class="px-4 py-2 text-sm">
                        Batal
                    </button>

                    <button @click="confirmHapus" class="bg-red-500 text-white px-4 py-2 rounded text-sm">
                        Hapus
                    </button>
                </div>

            </div>
        </div>


    </AuthenticatedLayout>
</template>

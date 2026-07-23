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

const closeTambah = () => {
    formTambah.reset()
    formTambah.clearErrors()
    previewTambah.value = null
    showTambahModal.value = false
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
    formEdit.clearErrors()
    previewEdit.value = item.gambar ? `/storage/${item.gambar}` : null
    showEditModal.value = true
}

const closeEdit = () => {
    formEdit.clearErrors()
    showEditModal.value = false
    selectedItem.value = null
    previewEdit.value = null
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
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">Manajemen Properti</h1>
                <p class="text-sm text-gray-500">Kelola data properti yang tersedia</p>
            </div>
            <button @click="showTambahModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm self-start sm:self-auto">
                + Tambah Properti
            </button>
        </div>

        <!-- EMPTY STATE (shared) -->
        <div v-if="props.properties.data.length === 0" class="bg-white rounded-xl shadow text-center p-8 text-gray-500">
            Belum ada data properti
        </div>

        <!-- TABLE — desktop / tablet -->
        <div v-else class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-[1100px] text-sm border-collapse">
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
                    <tbody>
                        <tr v-for="item in props.properties.data" :key="item.id" class="border-b hover:bg-gray-50">
                            <td class="p-3 whitespace-nowrap">
                                <img v-if="item.gambar" :src="`/storage/${item.gambar}`"
                                    class="w-16 h-12 object-cover rounded" />
                                <div v-else
                                    class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">
                                    No img
                                </div>
                            </td>

                            <td class="p-3 whitespace-nowrap font-medium">
                                {{ item.nama_properti }}
                            </td>

                            <td class="p-3 whitespace-nowrap text-gray-600">
                                {{ item.kategori?.nama_kategori || '-' }}
                            </td>

                            <td class="p-3 text-gray-600 max-w-[300px] break-words">
                                <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.lokasi)}`"
                                    target="_blank" class="text-blue-600 hover:underline">
                                    📍 {{ item.lokasi }}
                                </a>
                            </td>

                            <td class="p-3 text-right font-semibold whitespace-nowrap">
                                {{ formatRupiah(item.harga) }}
                            </td>

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
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARDS — mobile -->
        <div v-if="props.properties.data.length > 0" class="md:hidden space-y-3">
            <div
                v-for="item in props.properties.data"
                :key="item.id"
                class="bg-white rounded-xl shadow p-4 space-y-3"
            >
                <div class="flex gap-3">
                    <img v-if="item.gambar" :src="`/storage/${item.gambar}`"
                        class="w-20 h-16 object-cover rounded shrink-0" />
                    <div v-else
                        class="w-20 h-16 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400 shrink-0">
                        No img
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-800 truncate">{{ item.nama_properti }}</div>
                        <div class="text-xs text-gray-500">{{ item.kategori?.nama_kategori || '-' }}</div>
                        <div class="font-semibold text-sm mt-1">{{ formatRupiah(item.harga) }}</div>
                    </div>
                </div>

                <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.lokasi)}`"
                    target="_blank" class="block text-sm text-blue-600 hover:underline break-words">
                    📍 {{ item.lokasi }}
                </a>

                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                    <button @click="openEdit(item)"
                        class="flex-1 bg-yellow-400 text-white px-3 py-2 rounded text-xs hover:opacity-90">
                        Edit
                    </button>
                    <button @click="openHapus(item)"
                        class="flex-1 bg-red-500 text-white px-3 py-2 rounded text-xs hover:opacity-90">
                        Hapus
                    </button>
                </div>
            </div>
        </div>


        <!-- =========================
         MODAL TAMBAH
    ========================= -->
        <div v-if="showTambahModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="bg-white p-6 rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

                <h2 class="text-lg font-semibold mb-3 text-gray-800">Tambah Properti</h2>

                <form @submit.prevent="submitTambah" class="space-y-3">

                    <!-- KATEGORI -->
                    <div>
                        <select v-model="formTambah.kategori_id" class="w-full border p-2 rounded">
                            <option value="">Pilih Kategori</option>
                            <option v-for="kat in props.kategori" :key="kat.id" :value="kat.id">
                                {{ kat.nama_kategori }}
                            </option>
                        </select>
                        <p v-if="formTambah.errors.kategori_id" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.kategori_id }}
                        </p>
                    </div>

                    <!-- NAMA -->
                    <div>
                        <input v-model="formTambah.nama_properti" placeholder="Nama Properti"
                            class="w-full border p-2 rounded" />
                        <p v-if="formTambah.errors.nama_properti" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.nama_properti }}
                        </p>
                    </div>

                    <!-- LOKASI -->
                    <div>
                        <input v-model="formTambah.lokasi" placeholder="Lokasi" class="w-full border p-2 rounded" />
                        <p v-if="formTambah.errors.lokasi" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.lokasi }}
                        </p>
                    </div>

                    <!-- HARGA -->
                    <div>
                        <input :value="formatRupiahInput(formTambah.harga)"
                            @input="formTambah.harga = parseRupiah($event.target.value)" placeholder="Harga"
                            class="w-full border p-2 rounded" />
                        <p v-if="formTambah.errors.harga" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.harga }}
                        </p>
                    </div>

                    <!-- DESKRIPSI -->
                    <div>
                        <textarea v-model="formTambah.deskripsi" placeholder="Deskripsi"
                            class="w-full border p-2 rounded"></textarea>
                        <p v-if="formTambah.errors.deskripsi" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.deskripsi }}
                        </p>
                    </div>

                    <!-- GAMBAR -->
                    <div>
                        <input type="file" @change="onGambarTambah" />
                        <p v-if="formTambah.errors.gambar" class="text-xs text-red-500 mt-1">
                            {{ formTambah.errors.gambar }}
                        </p>
                    </div>

                    <!-- PREVIEW -->
                    <img v-if="previewTambah" :src="previewTambah" class="w-full h-40 object-cover rounded mt-2" />

                    <!-- TOMBOL (satu-satunya, di paling bawah) -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="closeTambah" class="px-4 py-2 text-sm">
                            Batal
                        </button>
                        <button type="submit" :disabled="formTambah.processing"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm disabled:opacity-50">
                            {{ formTambah.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =========================
         MODAL EDIT
    ========================= -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">

            <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] flex flex-col">

                <div class="p-4 border-b font-semibold">
                    Edit Properti
                </div>

                <div class="p-4 overflow-y-auto space-y-3">

                    <form @submit.prevent="submitEdit" class="space-y-3">

                        <div>
                            <select v-model="formEdit.kategori_id" class="w-full border p-2 rounded">
                                <option value="">Pilih Kategori</option>
                                <option v-for="kat in props.kategori" :key="kat.id" :value="kat.id">
                                    {{ kat.nama_kategori }}
                                </option>
                            </select>
                            <p v-if="formEdit.errors.kategori_id" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.kategori_id }}
                            </p>
                        </div>

                        <div>
                            <input v-model="formEdit.nama_properti" placeholder="Nama Properti"
                                class="w-full border p-2 rounded" />
                            <p v-if="formEdit.errors.nama_properti" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.nama_properti }}
                            </p>
                        </div>

                        <div>
                            <input v-model="formEdit.lokasi" placeholder="Lokasi" class="w-full border p-2 rounded" />
                            <p v-if="formEdit.errors.lokasi" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.lokasi }}
                            </p>
                        </div>

                        <div>
                            <input :value="formatRupiahInput(formEdit.harga)"
                                @input="formEdit.harga = parseRupiah($event.target.value)" placeholder="Harga"
                                class="w-full border p-2 rounded" />
                            <p v-if="formEdit.errors.harga" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.harga }}
                            </p>
                        </div>

                        <div>
                            <textarea v-model="formEdit.deskripsi" placeholder="Deskripsi"
                                class="w-full border p-2 rounded"></textarea>
                            <p v-if="formEdit.errors.deskripsi" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.deskripsi }}
                            </p>
                        </div>

                        <div>
                            <input type="file" @change="onGambarEdit" />
                            <p v-if="formEdit.errors.gambar" class="text-xs text-red-500 mt-1">
                                {{ formEdit.errors.gambar }}
                            </p>
                        </div>

                        <img v-if="previewEdit" :src="previewEdit" class="w-full h-40 object-cover rounded mt-2" />

                    </form>

                </div>

                <div class="p-4 border-t flex justify-end gap-2">
                    <button type="button" @click="closeEdit">
                        Batal
                    </button>

                    <button @click="submitEdit" :disabled="formEdit.processing"
                        class="bg-yellow-500 text-white px-4 py-2 rounded disabled:opacity-50">
                        {{ formEdit.processing ? 'Menyimpan...' : 'Update' }}
                    </button>
                </div>

            </div>
        </div>


        <!-- =========================
     MODAL HAPUS
========================= -->
        <div v-if="showHapusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">

            <div class="bg-white rounded-xl w-full max-w-md p-6">

                <h2 class="text-lg font-semibold mb-2 text-gray-800">
                    Hapus Properti
                </h2>

                <p class="text-sm text-gray-600 mb-4">
                    Yakin ingin menghapus properti
                    <span class="font-semibold">
                        "{{ selectedItem?.nama_properti }}"
                    </span> ?
                </p>

                <img v-if="selectedItem?.gambar" :src="`/storage/${selectedItem.gambar}`"
                    class="w-full h-32 object-cover rounded mb-4" />

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
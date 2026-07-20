<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()

const props = defineProps({
    kategori: {
        type: Array,
        default: () => []
    }
})

// ==============================
// STATE MODAL
// ==============================
const showTambahModal = ref(false)
const showEditModal = ref(false)
const showHapusModal = ref(false)
const selectedItem = ref(null)

// ==============================
// TOAST
// ==============================
const toast = ref({ show: false, message: '', type: 'error' })
let toastTimeout = null

const showToast = (message, type = 'error') => {
    clearTimeout(toastTimeout)
    toast.value = { show: true, message, type }
    toastTimeout = setTimeout(() => {
        toast.value.show = false
    }, 3000)
}

// ==============================
// FORM TAMBAH
// ==============================
const formTambah = useForm({
    nama_kategori: '',
    deskripsi: ''
})

const submitTambah = () => {
    formTambah.post(route('categories.store'), {
        onSuccess: () => {
            formTambah.reset()
            showTambahModal.value = false
        }
    })
}

// ==============================
// FORM EDIT
// ==============================
const formEdit = useForm({
    nama_kategori: '',
    deskripsi: ''
})

const openEdit = (item) => {
    selectedItem.value = item
    formEdit.nama_kategori = item.nama_kategori
    formEdit.deskripsi = item.deskripsi || ''
    showEditModal.value = true
}

const submitEdit = () => {
    formEdit.put(route('categories.update', selectedItem.value.id), {
        onSuccess: () => {
            showEditModal.value = false
            selectedItem.value = null
        }
    })
}

// ==============================
// TOGGLE STATUS
// ==============================
const toggleStatus = (item) => {
    const nextStatus = (item.status_category ?? 'aktif') === 'aktif' ? 'tidak aktif' : 'aktif'

    router.patch(route('categories.toggleStatus', item.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showToast(`Status diubah menjadi "${nextStatus}".`, 'success')
        },
        onError: () => {
            showToast('Gagal mengubah status kategori.')
        }
    })
}

// ==============================
// HAPUS
// ==============================
const canHapus = (item) => {
    return item.properti_count === 0 && (item.status_category ?? 'aktif') === 'tidak aktif'
}

const openHapus = (item) => {
    if (item.properti_count > 0) {
        showToast(`Tidak bisa dihapus, masih ada ${item.properti_count} properti terkait.`)
        return
    }
    if ((item.status_category ?? 'aktif') === 'aktif') {
        showToast('Nonaktifkan dulu sebelum menghapus.')
        return
    }
    selectedItem.value = item
    showHapusModal.value = true
}

const confirmHapus = () => {
    router.delete(route('categories.destroy', selectedItem.value.id), {
        onSuccess: () => {
            showHapusModal.value = false
            selectedItem.value = null
            showToast('Kategori berhasil dihapus.', 'success')
        },
        onError: () => {
            showToast('Gagal menghapus kategori.')
        }
    })
}
</script>

<template>
    <AuthenticatedLayout>

        <!-- HEADER -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Kategori Properti</h1>
                <p class="text-sm text-gray-500">Kelola data kategori properti</p>
            </div>
            <button @click="showTambahModal = true"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:opacity-90 text-sm">
                + Tambah Kategori
            </button>
        </div>

        <!-- ALERT -->
        <div v-if="page.props.flash?.success" class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ page.props.flash.success }}
        </div>
        <div v-if="page.props.flash?.error" class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ page.props.flash.error }}
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Nama Kategori</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-center">Total Properti</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in props.kategori" :key="item.id" class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium">{{ item.nama_kategori }}</td>
                        <td class="p-3 text-gray-600">{{ item.deskripsi || '-' }}</td>
                        <td class="p-3 text-center">{{ item.properti_count }}</td>

                        <!-- TOGGLE STATUS -->
                        <td class="p-3 text-center">
                            <button
                                @click="toggleStatus(item)"
                                type="button"
                                role="switch"
                                :aria-checked="(item.status_category ?? 'aktif') === 'aktif'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                :class="(item.status_category ?? 'aktif') === 'aktif' ? 'bg-green-500' : 'bg-gray-300'"
                                :title="(item.status_category ?? 'aktif') === 'aktif' ? 'Aktif — klik untuk nonaktifkan' : 'Tidak Aktif — klik untuk aktifkan'"
                            >
                                <span
                                    class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                    :class="(item.status_category ?? 'aktif') === 'aktif' ? 'translate-x-6' : 'translate-x-1'"
                                ></span>
                            </button>
                        </td>

                        <!-- AKSI -->
                        <td class="p-3 text-center space-x-2">
                            <button @click="openEdit(item)"
                                class="bg-yellow-400 text-white px-3 py-1 rounded hover:opacity-90 text-xs">
                                Edit
                            </button>
                            <button @click="openHapus(item)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:opacity-90 text-xs">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <tr v-if="props.kategori.length === 0">
                        <td colspan="5" class="text-center p-4 text-gray-500">
                            Belum ada data kategori
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ==============================
            MODAL TAMBAH
        ============================== -->
        <div v-if="showTambahModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Tambah Kategori</h2>

                <form @submit.prevent="submitTambah" class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input v-model="formTambah.nama_kategori"
                            class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-200"
                            placeholder="Masukkan nama kategori" />
                        <div v-if="formTambah.errors.nama_kategori" class="text-red-500 text-xs mt-1">
                            {{ formTambah.errors.nama_kategori }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Deskripsi</label>
                        <textarea v-model="formTambah.deskripsi"
                            class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-200" rows="3"
                            placeholder="Deskripsi (opsional)"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showTambahModal = false; formTambah.reset()"
                            class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-50 text-sm">
                            Batal
                        </button>
                        <button type="submit" :disabled="formTambah.processing"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:opacity-90 disabled:opacity-50 text-sm">
                            {{ formTambah.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==============================
             MODAL EDIT
        ============================== -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Edit Kategori</h2>

                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input v-model="formEdit.nama_kategori"
                            class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-200" />
                        <div v-if="formEdit.errors.nama_kategori" class="text-red-500 text-xs mt-1">
                            {{ formEdit.errors.nama_kategori }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Deskripsi</label>
                        <textarea v-model="formEdit.deskripsi"
                            class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-200"
                            rows="3"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-50 text-sm">
                            Batal
                        </button>
                        <button type="submit" :disabled="formEdit.processing"
                            class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:opacity-90 disabled:opacity-50 text-sm">
                            {{ formEdit.processing ? 'Menyimpan...' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==============================
             MODAL HAPUS
        ============================== -->
        <div v-if="showHapusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6">
                <h2 class="text-lg font-semibold mb-2">Hapus Kategori</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Yakin ingin menghapus kategori
                    <span class="font-semibold text-gray-800">{{ selectedItem?.nama_kategori }}</span>?
                    Aksi ini tidak bisa dibatalkan.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="showHapusModal = false"
                        class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-50 text-sm">
                        Batal
                    </button>
                    <button @click="confirmHapus"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:opacity-90 text-sm">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- ==============================
             TOAST
        ============================== -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="toast.show"
                class="fixed bottom-6 right-6 z-[60] px-4 py-3 rounded-lg shadow-lg text-sm text-white"
                :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
            >
                {{ toast.message }}
            </div>
        </Transition>

    </AuthenticatedLayout>
</template>
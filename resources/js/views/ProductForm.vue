<template>
  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="fw-bold mb-4">
          {{ isEdit ? 'Edit Produk' : 'Tambah Produk' }}
        </h4>

        <form @submit.prevent="submitForm" enctype="multipart/form-data" class="row g-3">
          <!-- Nama -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Produk</label>
            <input
              v-model="form.name"
              type="text"
              class="form-control"
              placeholder="Masukkan nama produk"
              required
            />
          </div>

          <!-- Harga -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Harga</label>
            <input
              v-model="form.price"
              type="number"
              class="form-control"
              placeholder="Masukkan harga"
              required
            />
          </div>

          <!-- Kategori -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Kategori</label>
            <input
              v-model="form.category"
              type="text"
              class="form-control"
              placeholder="Masukkan kategori"
            />
          </div>

          <!-- Gambar -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Gambar Produk</label>
            <input type="file" class="form-control" @change="onFileChange" accept="image/*" />

            <div v-if="preview" class="mt-2">
              <img :src="preview" alt="Preview" class="img-thumbnail" style="max-height: 150px;" />
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="col-12">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea
              v-model="form.description"
              class="form-control"
              rows="4"
              placeholder="Tulis deskripsi produk..."
            ></textarea>
          </div>

          <!-- Tombol -->
          <div class="col-12 text-end">
            <button type="button" class="btn btn-secondary me-2" @click="router.back()">
              <i class="bi bi-arrow-left"></i> Batal
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i>
              {{ isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const isEdit = !!route.params.id

const form = reactive({
  name: '',
  description: '',
  price: '',
  category: '',
  image: null,
})

const preview = ref(null)

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.image = file
    preview.value = URL.createObjectURL(file)
  }
}

const fetchData = async () => {
  if (isEdit) {
    try {
      const res = await axios.post('/api/products/getDataProductDetail', { id: route.params.id })
      const product = res.data.data || res.data  // handle dua kemungkinan

      // isi form reaktif
      form.name = product.name || ''
      form.description = product.description || ''
      form.price = product.price || ''
      form.category = product.category || ''

      if (product.image) {
        preview.value = `/storage/${product.image}`
      }
    } catch (error) {
      console.error('Gagal memuat data produk:', error)
    }
  }
}


const submitForm = async () => {
  const data = new FormData()
  Object.entries(form).forEach(([k, v]) => {
    if (v !== null && v !== undefined) data.append(k, v)
  })

  if (isEdit) {
    data.append('id', route.params.id)
    await axios.post('/api/products/editProduct', data)
  } else {
    await axios.post('/api/products/createProduct', data)
  }

  router.push('/')
}


onMounted(fetchData)
</script>

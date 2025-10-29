<template>
  <div class="container py-5">
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="product" class="card shadow-sm mx-auto" style="max-width: 600px;">
      <img
        v-if="product.image"
        :src="`/storage/${product.image}`"
        class="card-img-top"
        alt="Gambar Produk"
        style="height: 320px; object-fit: cover;"
      />

      <div class="card-body">
        <h4 class="card-title fw-bold">{{ product.name }}</h4>
        <p class="text-muted">{{ product.description }}</p>
        <h5 class="text-primary">Rp {{ Number(product.price).toLocaleString() }}</h5>

        <div class="mt-4 d-flex justify-content-between">
          <button
            class="btn btn-outline-primary w-50 me-2"
            @click="$router.push(`/products/${product.id}/edit`)"
          >
            <i class="bi bi-pencil-square me-1"></i> Edit
          </button>

          <button class="btn btn-outline-danger w-50" @click="deleteProduct">
            <i class="bi bi-trash me-1"></i> Hapus
          </button>
        </div>
      </div>
    </div>

    <div v-else class="text-center text-muted py-5">
      Produk tidak ditemukan.
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const loading = ref(false)

const fetchProduct = async () => {
  loading.value = true
  try {
    const res = await axios.post('/api/products/getDataProductDetail', {
      id: route.params.id,
    })
    product.value = res.data.data ?? res.data // sesuaikan kalau API kamu bungkus data di "data"
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const deleteProduct = async () => {
  if (!confirm('Yakin ingin menghapus produk ini?')) return

  try {
    await axios.delete('/api/products/deleteProduct', {
      data: { id: route.params.id },
    })
    alert('Produk berhasil dihapus.')
    router.push('/')
  } catch (error) {
    console.error(error)
    alert('Gagal menghapus produk.')
  }
}

onMounted(fetchProduct)
</script>

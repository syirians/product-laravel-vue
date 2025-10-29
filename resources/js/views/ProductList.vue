<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h4 fw-bold mb-0">Daftar Produk</h1>
      <button class="btn btn-primary" @click="$router.push('/products/create')">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
      </button>
    </div>

    <!-- Search bar -->
    <div class="input-group mb-4">
      <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
      <input
        type="text"
        class="form-control"
        placeholder="Cari produk..."
        v-model="search"
        @input="fetchProducts"
      />
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Product grid -->
    <div v-else class="row g-3">
      <div
        v-for="p in products.data"
        :key="p.id"
        class="col-md-4 col-sm-6 col-12"
      >
        <div class="card h-100 shadow-sm">
          <img
            v-if="p.image"
            :src="`/storage/${p.image}`"
            class="card-img-top"
            style="height: 180px; object-fit: cover;"
            alt="Product Image"
          />
          <div class="card-body">
            <h5 class="card-title mb-2">{{ p.name }}</h5>
            <p class="text-muted mb-3">Rp {{ Number(p.price).toLocaleString() }}</p>
            <button
              class="btn btn-outline-primary w-100"
              @click="$router.push(`/products/${p.id}`)"
            >
              <i class="bi bi-eye me-1"></i> Detail
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- If no products -->
    <div v-if="!loading && (!products.data || products.data.length === 0)" class="text-center py-5 text-muted">
      Tidak ada produk ditemukan.
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const products = ref({ data: [] })
const loading = ref(false)
const search = ref('')

const fetchProducts = async () => {
  loading.value = true
  try {
    const res = await axios.post('/api/products/getDataProduct', { search: search.value })
    products.value = res.data
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchProducts)
</script>

import { createRouter, createWebHistory } from 'vue-router'
import ProductList from '../views/ProductList.vue'
import ProductForm from '../views/ProductForm.vue'
import ProductDetail from '../views/ProductDetail.vue'

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'products', component: ProductList },
    { path: '/products/create', name: 'create', component: ProductForm },
    { path: '/products/:id', name: 'detail', component: ProductDetail },
    { path: '/products/:id/edit', name: 'edit', component: ProductForm, props: true },
  ]
})

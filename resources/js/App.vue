<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-indigo-600">EShop</h1>
          </div>
          <div class="flex items-center gap-4">
            <button
              @click="showCart = !showCart"
              class="relative p-2 text-gray-600 hover:text-gray-900"
            >
              <span class="text-2xl">🛒</span>
              <span v-if="cartItemCount > 0" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ cartItemCount }}
              </span>
            </button>
            <button
              @click="logout"
              class="px-3 py-2 text-gray-700 hover:text-gray-900"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
      <div v-if="showCart" class="mb-8">
        <CartView
          :cartItems="cartItems"
          :total="cartTotal"
          @update-quantity="updateCartQuantity"
          @remove-item="removeFromCart"
          @checkout="checkout"
        />
      </div>

      <div v-else>
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Browse Products</h2>
        <ProductList
          :products="products"
          @add-to-cart="addToCart"
          @load-products="loadProducts"
          @search="handleSearch"
        />
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import ProductList from './components/ProductList.vue';
import CartView from './components/CartView.vue';
import api from '@/services/api';

interface Product {
  id: number;
  name: string;
  price: number;
}

interface CartItem {
  id: number;
  product_id: number;
  quantity: number;
  product: Product;
}

const products = ref<Product[]>([]);
const cartItems = ref<CartItem[]>([]);
const cartTotal = ref<number>(0);
const showCart = ref<boolean>(false);
const searchQuery = ref<string>('');

const cartItemCount = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
);

// ---------------- API CALLS ----------------

const loadProducts = async (search = '') => {
  const params = search ? { search } : {};
  const { data } = await api.get('/products', { params });
  products.value = data;
};

const loadCart = async () => {

  const { data } = await api.get('/cart');
  cartItems.value = data.items;
  cartTotal.value = data.total;
};

const addToCart = async (product: Product, quantity = 1) => {
  const { data } = await api.post('/cart/add', {
    product_id: product.id,
    quantity,
  });

  cartItems.value = data.cart.items;
  cartTotal.value = data.cart.total;
};

const updateCartQuantity = async (cartItemId: number, quantity: number) => {
  const { data } = await api.put(`/cart/items/${cartItemId}`, { quantity });
  cartItems.value = data.cart.items;
  cartTotal.value = data.cart.total;
};

const removeFromCart = async (cartItemId: number) => {
  const { data } = await api.delete(`/cart/items/${cartItemId}`);
  cartItems.value = data.cart.items;
  cartTotal.value = data.cart.total;
};

const checkout = async () => {
  await api.post('/checkout');
  cartItems.value = [];
  cartTotal.value = 0;
  showCart.value = false;
  alert('Order placed successfully!');
};

const logout = () => {
  localStorage.removeItem('api_token');
  window.location.href = '/login';
};

const handleSearch = (query: string) => {
  searchQuery.value = query;
  loadProducts(query);
};



// ---------------- LIFECYCLE ----------------

onMounted(() => {
  const token = localStorage.getItem('api_token');
  if (!token) {
    window.location.href = '/login';
    return;
  }

  loadProducts();
  loadCart();
});
</script>

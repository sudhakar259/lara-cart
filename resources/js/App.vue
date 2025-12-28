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
        />
      </div>
    </div>
  </div>
</template>

<!-- <script>
import { ref, computed, onMounted } from 'vue';
import ProductList from './components/ProductList.vue';
import CartView from './components/CartView.vue';
import api from '@/services/api';
// const axios = window.axios;

export default {
  components: {
    ProductList,
    CartView,
  },
  setup() {
    const products = ref([]);
    const cartItems = ref([]);
    const cartTotal = ref(0);
    const showCart = ref(false);

    const cartItemCount = computed(() => {
      return cartItems.value.reduce((total, item) => total + item.quantity, 0);
    });

    const loadProducts = async () => {
      try {
        const response = await axios.get('/api/products');
        products.value = response.data;
      } catch (error) {
        console.error('Failed to load products:', error);
      }
    };

    const loadCart = async () => {
      try {
        const response = await api.get('/cart');
        cartItems.value = response.data.items;
        cartTotal.value = response.data.total;
      } catch (error) {
        console.error('Failed to load cart:', error);
      }
    };

    const addToCart = async (product, quantity) => {
      try {
        const response = await api.post('/cart/add', {
            product_id: product.id,
            quantity,
        });

        cartItems.value = response.data.cart.items;
        cartTotal.value = response.data.cart.total;
        alert('Product added to cart!');
      } catch (error) {
        console.error('Failed to add to cart:', error);
        alert('Failed to add product to cart');
      }
    };

    const updateCartQuantity = async (cartItemId, quantity) => {
      try {
        const response = await axios.put(`/api/cart/items/${cartItemId}`, {
          quantity: quantity,
        });
        cartItems.value = response.data.cart.items;
        cartTotal.value = response.data.cart.total;
      } catch (error) {
        console.error('Failed to update cart:', error);
      }
    };

    const removeFromCart = async (cartItemId) => {
      try {
        const response = await axios.delete(`/api/cart/items/${cartItemId}`);
        cartItems.value = response.data.cart.items;
        cartTotal.value = response.data.cart.total;
      } catch (error) {
        console.error('Failed to remove from cart:', error);
      }
    };

    const checkout = async () => {
      try {
        const response = await axios.post('/api/checkout');
        alert('Order placed successfully!');
        cartItems.value = [];
        cartTotal.value = 0;
        showCart.value = false;
      } catch (error) {
        console.error('Checkout failed:', error);
        alert('Checkout failed: ' + (error.response?.data?.message || 'Unknown error'));
      }
    };

    const logout = async () => {
      try {
        // Clear the token
        localStorage.removeItem('api_token');
        // Use web logout to destroy session
        await axios.post('/logout');
        window.location.href = '/';
      } catch (error) {
        console.error('Logout failed:', error);
      }
    };


    onMounted(() => {
      // Check if user is authenticated
      const token = localStorage.getItem('api_token');
      if (!token) {
        window.location.href = '/login';
        return;
      }
      loadProducts();
      loadCart();
    });

    return {
      products,
      cartItems,
      cartTotal,
      cartItemCount,
      showCart,
      addToCart,
      updateCartQuantity,
      removeFromCart,
      checkout,
      logout,
      loadProducts,
    };
  },
};
</script> -->
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

const cartItemCount = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
);

// ---------------- API CALLS ----------------

const loadProducts = async () => {
  const { data } = await api.get('/products');
  products.value = data;
};

const loadCart = async () => {
  debugger;

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

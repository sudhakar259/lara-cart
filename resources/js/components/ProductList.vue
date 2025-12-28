<template>
  <div class="mb-6">
    <input
      v-model="searchQuery"
      @input="debouncedSearch"
      type="text"
      placeholder="Search products..."
      class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
    />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div
      v-for="product in products"
      :key="product.id"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition"
    >
      <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ product.description || 'No description' }}</p>

        <div class="flex justify-between items-center mb-4">
          <span class="text-2xl font-bold text-indigo-600">${{ Number(product.price).toFixed(2) }}</span>
          <span class="text-sm text-gray-600">
            Stock: {{ product.stock_quantity }}
          </span>
        </div>

        <div v-if="product.stock_quantity > 0" class="space-y-2">
          <input
            v-model.number="quantities[product.id]"
            type="number"
            min="1"
            :max="product.stock_quantity"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="Quantity"
          />
          <button
            @click="addProduct(product)"
            class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition font-medium"
          >
            Add to Cart
          </button>
        </div>
        <div v-else class="text-red-600 font-medium">Out of Stock</div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue';

export default {
  props: {
    products: {
      type: Array,
      required: true,
    },
  },
  emits: ['add-to-cart', 'load-products', 'search'],
  setup(props, { emit }) {
    const quantities = ref({});
    const searchQuery = ref('');

    let debounceTimer = null;
    const debouncedSearch = () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        emit('search', searchQuery.value);
      }, 300);
    };

    const addProduct = (product) => {
      const quantity = quantities.value[product.id] || 1;
      if (quantity < 1) {
        alert('Please enter a valid quantity');
        return;
      }
      emit('add-to-cart', product, quantity);
      quantities.value[product.id] = 1;
    };

    return {
      quantities,
      searchQuery,
      debouncedSearch,
      addProduct,
    };
  },
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Shopping Cart</h2>

    <div v-if="cartItems.length === 0" class="text-gray-600 text-center py-8">
      Your cart is empty
    </div>

    <div v-else>
      <div class="space-y-4 mb-6">
        <div 
          v-for="item in cartItems" 
          :key="item.id"
          class="flex justify-between items-center p-4 bg-gray-50 rounded-lg"
        >
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900">{{ item.product.name }}</h3>
            <p class="text-gray-600 text-sm">${{ Number(item.price).toFixed(2) }} each</p>
          </div>

          <div class="flex items-center gap-4">
            <input 
              :value="item.quantity"
              @change="(e) => updateQuantity(item.id, parseInt(e.target.value))"
              type="number" 
              min="1" 
              class="w-16 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <span class="text-lg font-semibold text-gray-900 w-20">
              ${{ (item.quantity * item.price).toFixed(2) }}
            </span>
            <button 
              @click="removeItem(item.id)"
              class="text-red-600 hover:text-red-800 font-medium"
            >
              Remove
            </button>
          </div>
        </div>
      </div>

      <div class="border-t pt-4 mb-6">
        <div class="flex justify-between items-center text-xl font-bold">
          <span>Total:</span>
          <span class="text-indigo-600">${{ Number(total).toFixed(2) }}</span>
        </div>
      </div>

      <button 
        @click="handleCheckout"
        :disabled="loading"
        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-bold text-lg disabled:bg-gray-400"
      >
        {{ loading ? 'Processing...' : 'Proceed to Checkout' }}
      </button>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  props: {
    cartItems: {
      type: Array,
      required: true,
    },
    total: {
      type: [Number, String],
      required: true,
    },
  },
  emits: ['update-quantity', 'remove-item', 'checkout'],
  setup(props, { emit }) {
    const loading = ref(false);

    const updateQuantity = (cartItemId, quantity) => {
      if (quantity < 1) {
        alert('Quantity must be at least 1');
        return;
      }
      emit('update-quantity', cartItemId, quantity);
    };

    const removeItem = (cartItemId) => {
      if (confirm('Remove this item from cart?')) {
        emit('remove-item', cartItemId);
      }
    };

    const handleCheckout = async () => {
      loading.value = true;
      emit('checkout');
      setTimeout(() => {
        loading.value = false;
      }, 1000);
    };

    return {
      loading,
      updateQuantity,
      removeItem,
      handleCheckout,
    };
  },
};
</script>

<script setup>
import { ref, computed } from 'vue'

// Mock Data for Bakery Products
const products = ref([
  { id: 1, name: 'Sourdough Bread', price: 4.50, stock: 15, category: 'Bread' },
  { id: 2, name: 'Chocolate Croissant', price: 3.20, stock: 24, category: 'Pastry' },
  { id: 3, name: 'Gluten-Free Blueberry Muffin', price: 3.80, stock: 8, category: 'Muffin' },
  { id: 4, name: 'Strawberry Tart', price: 5.00, stock: 6, category: 'Pastry' },
  { id: 5, name: 'Baguette', price: 2.50, stock: 30, category: 'Bread' },
  { id: 6, name: 'Red Velvet Cupcake', price: 3.50, stock: 12, category: 'Cake' },
])

// State Variables
const searchQuery = ref('')
const paymentMode = ref('') // 'card' or 'mpesa'
const cart = ref([])
const mpesaNumber = ref('')
const cardNumber = ref('')

// Filter products dynamically based on search input
const filteredProducts = computed(() => {
  return products.value.filter(product => 
    product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    product.category.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Shopping Cart Actions
const addToCart = (product) => {
  const existingItem = cart.value.find(item => item.id === product.id)
  if (existingItem) {
    if (existingItem.quantity < product.stock) {
      existingItem.quantity++
    }
  } else {
    cart.value.push({ ...product, quantity: 1 })
  }
}

const removeFromCart = (index) => {
  cart.value.splice(index, 1)
}

// Totals computations
const cartTotal = computed(() => {
  return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

// Handle Checkout Submission
const handleCheckout = () => {
  if (cart.value.length === 0) return alert('Your cart is empty!')
  if (!paymentMode.value) return alert('Please select a payment method!')
  
  alert(`Processing order of $${cartTotal.value.toFixed(2)} via ${paymentMode.value.toUpperCase()}!`)
  // Clear cart/fields after success
  cart.value = []
  paymentMode.value = ''
  mpesaNumber.value = ''
  cardNumber.value = ''
}
</script>

<template>
  <div class="space-y-6">
    <!-- Top Header & Live Search Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-[#16161a]/70 border border-white/5 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.2)]">
      <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0]">Point of Sale</h1>
        <p class="text-sm text-[#94A3B8]">Process real-time bakery orders and customer checkouts.</p>
      </div>
      <div class="relative w-full md:w-80">
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="Search products or categories..." 
          class="w-full px-4 py-2.5 bg-[#121214]/80 border border-white/10 rounded-lg text-[#E2E8F0] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/15 text-sm"
        />
      </div>
    </div>

    <!-- Main Workspace Split: Products Left, Cart & Payment Right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left: Products Grid Catalog -->
      <div class="lg:col-span-2">
        <div v-if="filteredProducts.length === 0" class="text-center py-12 text-[#94A3B8]">
          No products match your search.
        </div>
        
        <div class="grid grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-4">
          <div 
            v-for="product in filteredProducts" 
            :key="product.id"
            class="bg-[#16161a]/70 border border-white/5 rounded-xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.2)] transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/30 flex flex-col justify-between"
          >
            <div>
              <span class="text-xs font-semibold px-2 py-0.5 bg-blue-500/10 text-[#60A5FA] border border-blue-500/20 rounded-md">
                {{ product.category }}
              </span>
              <h3 class="text-lg font-medium text-[#E2E8F0] mt-2">{{ product.name }}</h3>
              <p class="text-sm text-[#94A3B8] mt-1">In Stock: {{ product.stock }}</p>
            </div>
            
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-white/5">
              <span class="text-xl font-bold text-white">${{ product.price.toFixed(2) }}</span>
              <button 
                @click="addToCart(product)"
                :disabled="product.stock === 0"
                class="px-3 py-1.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              >
                Add
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Cart & Payment Checkout Station -->
      <div class="space-y-6">
        <div class="bg-[#16161a]/80 border border-white/5 rounded-xl p-6 shadow-[0_4px_20px_rgba(0,0,0,0.2)]">
          <h2 class="text-lg font-bold text-white mb-4 flex justify-between items-center">
            <span>Current Order</span>
            <span class="text-xs font-normal text-[#94A3B8]">{{ cart.length }} items</span>
          </h2>

          <!-- Cart Items List -->
          <div class="space-y-3 max-h-60 overflow-y-auto mb-6 pr-1">
            <p v-if="cart.length === 0" class="text-sm text-[#94A3B8] text-center py-6">
              Cart is currently empty.
            </p>
            <div 
              v-for="(item, index) in cart" 
              :key="item.id"
              class="flex items-center justify-between text-sm bg-[#121214]/60 p-2.5 rounded-lg border border-white/5"
            >
              <div>
                <p class="font-medium text-[#E2E8F0]">{{ item.name }}</p>
                <p class="text-xs text-[#94A3B8]">{{ item.quantity }}x @ ${{ item.price.toFixed(2) }}</p>
              </div>
              <div class="flex items-center gap-3">
                <span class="font-semibold text-white">${{ (item.price * item.quantity).toFixed(2) }}</span>
                <button @click="removeFromCart(index)" class="text-red-400 hover:text-red-300 text-xs">✕</button>
              </div>
            </div>
          </div>

          <!-- Select Payment Mode -->
          <div class="border-t border-white/5 pt-4 mb-6">
            <label class="text-sm font-medium text-[#94A3B8] block mb-3">Payment Mode</label>
            <div class="grid grid-cols-2 gap-3">
              <!-- Card Option Button -->
              <button 
                type="button"
                @click="paymentMode = 'card'"
                :class="paymentMode === 'card' ? 'border-[#3B82F6] bg-blue-500/10 text-[#60A5FA]' : 'border-white/10 bg-[#121214]/80 text-[#94A3B8]'"
                class="flex flex-col items-center justify-center p-3 border rounded-xl font-medium transition-all"
              >
                <span class="text-lg mb-1">💳</span>
                <span class="text-xs">Card Payment</span>
              </button>

              <!-- Mpesa Option Button -->
              <button 
                type="button"
                @click="paymentMode = 'mpesa'"
                :class="paymentMode === 'mpesa' ? 'border-green-500 bg-green-500/10 text-green-400' : 'border-white/10 bg-[#121214]/80 text-[#94A3B8]'"
                class="flex flex-col items-center justify-center p-3 border rounded-xl font-medium transition-all"
              >
                <span class="text-lg mb-1">📱</span>
                <span class="text-xs">M-Pesa</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Conditional Form Fields Based on Selected Payment Mode -->
          <div v-if="paymentMode" class="mb-6 space-y-3 p-3 bg-[#121214]/50 border border-white/5 rounded-lg">
            <div v-if="paymentMode === 'card'">
              <label class="text-xs font-medium text-[#94A3B8]">Card Number</label>
              <input 
                v-model="cardNumber"
                type="text" 
                placeholder="4444 •••• •••• ••••" 
                class="w-full mt-1 px-3 py-2 bg-[#121214]/80 border border-white/10 rounded-lg text-sm text-[#E2E8F0] outline-none focus:border-[#3B82F6]"
              />
            </div>
            
            <div v-if="paymentMode === 'mpesa'">
              <label class="text-xs font-medium text-[#94A3B8]">M-Pesa Mobile Number</label>
              <input 
                v-model="mpesaNumber"
                type="text" 
                placeholder="07XX XXX XXX" 
                class="w-full mt-1 px-3 py-2 bg-[#121214]/80 border border-white/10 rounded-lg text-sm text-[#E2E8F0] outline-none focus:border-green-500"
              />
            </div>
          </div>

          <!-- Total & Checkout Action -->
          <div class="border-t border-white/10 pt-4 space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-sm text-[#94A3B8]">Total Amount</span>
              <span class="text-2xl font-bold text-white">${{ cartTotal.toFixed(2) }}</span>
            </div>
            <button 
              @click="handleCheckout"
              :disabled="cart.length === 0 || !paymentMode"
              class="w-full py-3 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-semibold rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed text-center"
            >
              Complete Checkout
            </button>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

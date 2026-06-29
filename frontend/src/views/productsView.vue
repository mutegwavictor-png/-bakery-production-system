<script setup>
import { ref, computed } from 'vue'
import ProductCard from '../components/productcard.vue'

// Task 1: Added 4 more products based on capstone spec
const products = ref([
  {
    id: 1,
    name: 'White Bread',
    category: 'bread',
    selling_price: 60,
    shelf_life_hours: 14,
    unit: 'loaf',
    is_active: true
  },
  {
    id: 2,
    name: 'Chocolate Cake',
    category: 'cake',
    selling_price: 150,
    shelf_life_hours: 48,
    unit: 'piece',
    is_active: true
  },
  {
    id: 3,
    name: 'Croissant',
    category: 'pastry',
    selling_price: 120,
    shelf_life_hours: 12,
    unit: 'piece',
    is_active: true
  },
  {
    id: 4,
    name: 'Burger Bun',
    category: 'bun',
    selling_price: 200,
    shelf_life_hours: 8,
    unit: 'piece',
    is_active: false
  },
  {
    id: 5,
    name: 'Brown Bread',
    category: 'bread',
    selling_price: 65,
    shelf_life_hours: 24,
    unit: 'loaf',
    is_active: true
  },
  {
    id: 6,
    name: 'Cinnamon Roll',
    category: 'pastry',
    selling_price: 40,
    shelf_life_hours: 12,
    unit: 'piece',
    is_active: true
  },
  {
    id: 7,
    name: 'Meat Pie',
    category: 'pastry',
    selling_price: 80,
    shelf_life_hours: 8,
    unit: 'piece',
    is_active: true
  },
  {
    id: 8,
    name: 'Chapati',
    category: 'bread',
    selling_price: 20,
    shelf_life_hours: 8,
    unit: 'piece',
    is_active: true
  },
  {
    id: 9,
    name: 'Sausage Roll',
    category: 'pastry',
    selling_price: 50,
    shelf_life_hours: 12,
    unit: 'piece',
    is_active: true
  },
  {
    id: 10,
    name: 'Vanilla Cupcake',
    category: 'cake',
    selling_price: 200,
    shelf_life_hours: 48,
    unit: 'piece',
    is_active: true
  }
])

// Task 4 & 5: Filter active/inactive, Search, Category
const showInactive = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('all')

// Currency configuration
const currencies = {
  KES: 1,
  USD: 0.0076,
  EUR: 0.0069,
  GBP: 0.0060
}
const selectedCurrency = ref('KES')
const currentExchangeRate = computed(() => currencies[selectedCurrency.value])

// Task 2: Sale confirmation panel state
const selectedProduct = ref(null)
const saleQuantity = ref(1)

const saleTotal = computed(() => {
  if (!selectedProduct.value) return 0
  const convertedPrice = selectedProduct.value.selling_price * currentExchangeRate.value
  return convertedPrice * saleQuantity.value
})

const formattedSaleTotal = computed(() => {
  const fractionDigits = selectedCurrency.value === 'KES' ? 0 : 2
  return saleTotal.value.toLocaleString(undefined, {
    minimumFractionDigits: fractionDigits,
    maximumFractionDigits: fractionDigits
  })
})

const convertedSalePrice = computed(() => {
  if (!selectedProduct.value) return 0
  const fractionDigits = selectedCurrency.value === 'KES' ? 0 : 2
  return (selectedProduct.value.selling_price * currentExchangeRate.value).toLocaleString(undefined, {
    minimumFractionDigits: fractionDigits,
    maximumFractionDigits: fractionDigits
  })
})

const handleSellProduct = (product) => {
  selectedProduct.value = product
  saleQuantity.value = 1
}

const confirmSale = () => {console.log('Confirmed sale of', saleQuantity.value, selectedProduct.value.name)

alert(`Sale confirmed for ${selectedProduct.value.name} — Qty: ${saleQuantity.value}`)

selectedProduct.value = null}

const handleViewRecipe = (productId) => {
  console.log('Viewing recipe for product ID:', productId)
}

const filteredProducts = computed(() => {
  return products.value.filter(p => {
    // 1. Inactive filter
    if (!showInactive.value && !p.is_active) return false
    // 2. Category filter
    if (selectedCategory.value !== 'all' && p.category !== selectedCategory.value) return false
    // 3. Search filter
    if (searchQuery.value && !p.name.toLowerCase().includes(searchQuery.value.toLowerCase())) return false
    return true
  })
})

const handleToggleActive = (productId) => {
  const product = products.value.find(p => p.id === productId)
  if (product) {
    product.is_active = !product.is_active
  }
}

// Task 3: Summary counters
const urgentCount = computed(() => products.value.filter(p => p.shelf_life_hours <= 8).length)
const fastCount = computed(() => products.value.filter(p => p.shelf_life_hours > 8 && p.shelf_life_hours <= 12).length)
const longCount = computed(() => products.value.filter(p => p.shelf_life_hours > 48).length)
</script>

<template>
  <div class="products-view">
    <header class="view-header">
      <h2>Products Inventory</h2>
      <button class="btn-add">
        <span class="icon">+</span> Add Product
      </button>
    </header>

    <div class="search-filter-bar">
      <div class="search-input-wrapper">
        <span class="search-icon">🔍</span>
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Search products by name..." 
          class="search-input" 
        />
      </div>
      
      <select v-model="selectedCategory" class="category-select">
        <option value="all">All Categories</option>
        <option value="bread">Bread</option>
        <option value="cake">Cake</option>
        <option value="pastry">Pastry</option>
        <option value="bun">Bun</option>
      </select>

      <select v-model="selectedCurrency" class="category-select currency-select">
        <option value="KES">KES</option>
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
      </select>
    </div>

    <div class="controls-bar">
      <!-- Task 3: Summary counters -->
      <div class="summary-counters">
        <span class="counter urgent">🔴 {{ urgentCount }} urgent</span>
        <span class="counter warning">🟡 {{ fastCount }} fast sellers</span>
        <span class="counter safe">🟢 {{ longCount }} long shelf life</span>
      </div>
      
      <!-- Task 4: Filter checkbox -->
      <label class="filter-toggle">
        <input type="checkbox" v-model="showInactive" />
        <span class="custom-checkbox"></span>
        Show inactive products
      </label>
    </div>

    <div class="products-grid">
      <ProductCard 
        v-for="product in filteredProducts" 
        :key="product.id" 
        :product="product"
        :currency="selectedCurrency"
        :exchange-rate="currentExchangeRate"
        @sell-product="handleSellProduct"
        @view-recipe="handleViewRecipe"
        @toggle-active="handleToggleActive"
      />
    </div>

    <!-- Task 2: Sale Confirmation Panel -->
    <div v-if="selectedProduct" class="sale-panel-overlay" @click.self="selectedProduct = null">
      <div class="sale-panel">
        <h3>Sell {{ selectedProduct.name }}</h3>
        <div class="sale-details">
          <p class="sale-price">Price: {{ selectedCurrency }} {{ convertedSalePrice }}</p>
          <div class="qty-input">
            <label for="qty">Quantity</label>
            <input id="qty" type="number" min="1" v-model.number="saleQuantity" />
          </div>
          <p class="total">Total: <span>{{ selectedCurrency }} {{ formattedSaleTotal }}</span></p>
        </div>
        <div class="sale-actions">
          <button class="btn-cancel" @click="selectedProduct = null">Cancel</button>
          <button class="btn-confirm" @click="confirmSale">Confirm Sale</button>
        </div>
      </div>
      
    </div>
  </div>
</template>



<style scoped>

.products-view {
  padding: 1rem 0;
}

.view-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.view-header h2 {
  font-size: 2rem;
  color: #F8FAFC;
  font-weight: 700;
  letter-spacing: -0.5px;
}

.btn-add {
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-add:hover {
  background: linear-gradient(135deg, #60A5FA, #3B82F6);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}

.search-filter-bar {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.search-input-wrapper {
  flex: 1;
  position: relative;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 1rem;
  font-size: 1.1rem;
  opacity: 0.7;
}

.search-input {
  width: 100%;
  background: rgba(30, 41, 59, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1rem 1rem 1rem 3rem;
  border-radius: 12px;
  color: #F8FAFC;
  font-size: 1.05rem;
  font-family: inherit;
  transition: all 0.3s;
}

.search-input:focus {
  outline: none;
  border-color: #60A5FA;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
  background: rgba(30, 41, 59, 0.6);
}

.search-input::placeholder {
  color: #94A3B8;
}

.category-select {
  background: rgba(30, 41, 59, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 0 1.5rem;
  border-radius: 12px;
  color: #F8FAFC;
  font-size: 1.05rem;
  font-family: inherit;
  cursor: pointer;
  min-width: 160px;
}

.category-select:focus {
  outline: none;
  border-color: #60A5FA;
}

.category-select option {
  background: #1E293B;
  color: #F8FAFC;
}

.controls-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(30, 41, 59, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1rem 1.5rem;
  border-radius: 12px;
  margin-bottom: 2rem;
}

.summary-counters {
  display: flex;
  gap: 1rem;
}

.counter {
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  border: 1px solid transparent;
}
.counter.urgent { background: rgba(220, 38, 38, 0.1); color: #FCA5A5; border-color: rgba(220, 38, 38, 0.2); }
.counter.warning { background: rgba(217, 119, 6, 0.1); color: #FCD34D; border-color: rgba(217, 119, 6, 0.2); }
.counter.safe { background: rgba(16, 185, 129, 0.1); color: #A7F3D0; border-color: rgba(16, 185, 129, 0.2); }

.filter-toggle {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.95rem;
  color: #E2E8F0;
  cursor: pointer;
  user-select: none;
}

.filter-toggle input {
  accent-color: #3B82F6;
  width: 1.1rem;
  height: 1.1rem;
  cursor: pointer;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

/* Sale Confirmation Panel Styles */
.sale-panel-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.sale-panel {
  background: rgba(18, 18, 20, 0.85);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(59, 130, 246, 0.3);
  padding: 2.5rem;
  border-radius: 16px;
  width: 90%;
  max-width: 420px;
  box-shadow: 0 24px 48px rgba(0,0,0,0.4);
  color: #F8FAFC;
}

.sale-panel h3 {
  margin-top: 0;
  margin-bottom: 1.5rem;
  color: #F8FAFC;
  font-size: 1.6rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding-bottom: 1rem;
}

.sale-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.sale-price {
  font-size: 1.2rem;
  color: #94A3B8;
}

.qty-input {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(255, 255, 255, 0.05);
  padding: 0.75rem 1rem;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.qty-input label {
  font-weight: 500;
  color: #E2E8F0;
}

.qty-input input {
  width: 80px;
  padding: 8px;
  background: rgba(15, 23, 42, 0.6);
  border: 1px solid rgba(59, 130, 246, 0.3);
  color: white;
  border-radius: 6px;
  font-size: 1.1rem;
  text-align: center;
}

.qty-input input:focus {
  outline: none;
  border-color: #60A5FA;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.total {
  font-size: 1.4rem;
  color: #E2E8F0;
  margin-top: 0.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total span {
  font-weight: 700;
  color: #60A5FA;
  text-shadow: 0 0 20px rgba(96, 165, 250, 0.4);
}

.sale-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-cancel {
  padding: 12px 20px;
  background: rgba(255, 255, 255, 0.05);
  color: #E2E8F0;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
}
.btn-cancel:hover { background: rgba(255, 255, 255, 0.1); }

.btn-confirm {
  padding: 12px 20px;
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}
.btn-confirm:hover { 
  background: linear-gradient(135deg, #60A5FA, #3B82F6);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}
</style>
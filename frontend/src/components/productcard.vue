<script setup>
import { computed } from 'vue'

// ---------------------------------------------------------------
// PROPS: data this component receives from its parent
// Field names match our PRODUCTS table from the ERD exactly.
// When we connect to the Laravel API in Week 6, the JSON response
// will have these same field names — no renaming needed.
// ---------------------------------------------------------------
const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

// ---------------------------------------------------------------
// EMIT: events this component sends UP to its parent
// The parent decides what to do — this component just reports.
// Think of it like a cashier reporting a sale to the manager.
// ---------------------------------------------------------------
const emit = defineEmits(['sell-product', 'view-recipe', 'toggle-active'])

// ---------------------------------------------------------------
// COMPUTED: derived values that auto-recalculate when data changes
// These implement BUSINESS RULES from the capstone spec:
// "Products have a shelf life in hours. Expiry is calculated from
//  batch completion time."
// ---------------------------------------------------------------
const shelfLifeStatus = computed(() => {
  const hours = props.product.shelf_life_hours
  if (hours <= 8) return { label: 'Very short shelf life', class: 'urgent', icon: '🔴' }
  if (hours <= 12) return { label: 'Sells fast — prioritize', class: 'warning', icon: '🟡' }
  if (hours <= 24) return { label: 'Sell today', class: 'today', icon: '🟠' }
  if (hours <= 48) return { label: 'Multi-day shelf life', class: 'safe', icon: '🟢' }
  return { label: 'Long shelf life', class: 'long', icon: '🟢' }
})

// Format price with commas for readability: 1000 → 1,000
const formattedPrice = computed(() => {
  return props.product.selling_price.toLocaleString()
})

function handleSell() {
  emit('sell-product', props.product)
}

function handleViewRecipe() {
  emit('view-recipe', props.product.id)
}

function handleToggleActive() {
  emit('toggle-active', props.product.id)
}
</script>

<template>
  <div class="product-card" :class="{ 'inactive': !product.is_active }">
    <!-- Card header: product name + category badge -->
    <div class="card-header">
      <div class="header-main">
        <h3 class="product-name">{{ product.name }}</h3>
        <span :class="'category-badge category-' + product.category">
          {{ product.category }}
        </span>
      </div>
      <button class="btn-toggle" @click="handleToggleActive" :title="product.is_active ? 'Deactivate' : 'Activate'">
        {{ product.is_active ? '⏸' : '▶' }}
      </button>
    </div>

    <!-- Price display -->
    <div class="price-section">
      <span class="price">KES {{ formattedPrice }}</span>
      <span class="unit">per {{ product.unit }}</span>
    </div>

    <!-- Shelf life indicator — business rule visualization -->
    <div :class="'shelf-life shelf-' + shelfLifeStatus.class">
      <span class="shelf-icon">{{ shelfLifeStatus.icon }}</span>
      <span>{{ shelfLifeStatus.label }} ({{ product.shelf_life_hours }}h)</span>
    </div>

    <!-- Inactive product warning -->
    <div v-if="!product.is_active" class="inactive-banner">
      ⚠ Product inactive — not available for sale
    </div>

    <!-- Action buttons -->
    <div class="card-actions">
      <button class="btn-primary" @click="handleSell" :disabled="!product.is_active">
        Sell
      </button>
      <button class="btn-secondary" @click="handleViewRecipe">
        Recipe
      </button>
    </div>
  </div>
</template>

<style scoped>
.product-card {
  background: rgba(30, 41, 59, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  gap: 1rem;
  color: #E2E8F0;
  position: relative;
  overflow: hidden;
}

.product-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 4px;
  background: linear-gradient(90deg, #3B82F6, #60A5FA);
  opacity: 0.8;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(59, 130, 246, 0.15);
  border-color: rgba(59, 130, 246, 0.4);
}

.product-card.inactive {
  opacity: 0.5;
  filter: grayscale(80%);
}
.product-card.inactive::before {
  background: #4B5563;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.header-main {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.5rem;
}

.btn-toggle {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  cursor: pointer;
  font-size: 1rem;
  padding: 0.4rem;
  color: #94A3B8;
  border-radius: 8px;
  transition: all 0.2s;
}
.btn-toggle:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #F8FAFC;
}

.product-name {
  font-size: 1.25rem;
  font-weight: 600;
  color: #F8FAFC;
  margin: 0;
  letter-spacing: 0.5px;
}

.category-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.category-bread  { background: rgba(217, 119, 6, 0.15); color: #FCD34D; border-color: rgba(217, 119, 6, 0.3); }
.category-cake   { background: rgba(219, 39, 119, 0.15); color: #F9A8D4; border-color: rgba(219, 39, 119, 0.3); }
.category-pastry { background: rgba(79, 70, 229, 0.15); color: #C7D2FE; border-color: rgba(79, 70, 229, 0.3); }
.category-bun    { background: rgba(5, 150, 105, 0.15); color: #6EE7B7; border-color: rgba(5, 150, 105, 0.3); }

.price-section {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
  margin: 0.5rem 0;
}

.price {
  font-size: 1.6rem;
  font-weight: 700;
  color: #60A5FA;
  text-shadow: 0 0 20px rgba(96, 165, 250, 0.3);
}

.unit {
  font-size: 0.9rem;
  color: #94A3B8;
}

.shelf-life {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  padding: 8px 12px;
  border-radius: 8px;
  font-weight: 500;
  border: 1px solid transparent;
}

.shelf-icon { font-size: 1rem; }
.shelf-urgent  { background: rgba(220, 38, 38, 0.1); color: #FCA5A5; border-color: rgba(220, 38, 38, 0.2); }
.shelf-warning { background: rgba(217, 119, 6, 0.1); color: #FCD34D; border-color: rgba(217, 119, 6, 0.2); }
.shelf-today   { background: rgba(234, 88, 12, 0.1); color: #FDBA74; border-color: rgba(234, 88, 12, 0.2); }
.shelf-safe    { background: rgba(5, 150, 105, 0.1); color: #6EE7B7; border-color: rgba(5, 150, 105, 0.2); }
.shelf-long    { background: rgba(16, 185, 129, 0.1); color: #A7F3D0; border-color: rgba(16, 185, 129, 0.2); }

.inactive-banner {
  background: rgba(220, 38, 38, 0.15);
  color: #FCA5A5;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  border: 1px solid rgba(220, 38, 38, 0.3);
  text-align: center;
}

.card-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: auto;
}

.btn-primary, .btn-secondary {
  flex: 1;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-primary {
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  color: white;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-primary:hover:not(:disabled) { 
  background: linear-gradient(135deg, #60A5FA, #3B82F6);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}
.btn-primary:disabled { 
  background: #334155; 
  color: #94A3B8;
  box-shadow: none;
  cursor: not-allowed; 
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.05);
  color: #E2E8F0;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-secondary:hover { 
  background: rgba(255, 255, 255, 0.1); 
}
</style>
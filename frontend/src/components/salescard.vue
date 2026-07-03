<script setup>
import { ref } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const quantity = ref(1)

const addToCart = () => {
  if (quantity.value > 0) {
    alert(`Added ${quantity.value} ${props.product.unit}(s) of ${props.product.name} to cart!`)
    // Extendable: emit('add-to-cart', { productId: props.product.id, qty: quantity.value })
  }
}
</script>

<template>
  <div class="sales-card">
    <div class="badge">{{ product.category }}</div>
    <h3 class="title">{{ product.name }}</h3>
    
    <div class="price-section">
      <span class="price">${{ product.selling_price.toFixed(2) }}</span>
      <span class="unit">/ {{ product.unit }}</span>
    </div>

    <div class="shelf-life">
      ⏱️ Fresh for: {{ product.shelf_life_hours }} hours
    </div>

    <div class="action-row">
      <div class="quantity-selector">
        <button @click="quantity > 1 ? quantity-- : null" type="button">-</button>
        <input v-model.number="quantity" type="number" min="1" readonly />
        <button @click="quantity++" type="button">+</button>
      </div>
      
      <button @click="addToCart" class="sell-btn" type="button">
        🛒 Sell Item
      </button>
    </div>
  </div>
</template>

<style scoped>
.sales-card {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.25rem;
  background: #ffffff;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  position: relative;
  transition: transform 0.2s, box-shadow 0.2s;
}

.sales-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: #f1f5f9;
  color: #475569;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.25rem 0.5rem;
  border-radius: 20px;
}

.title {
  font-size: 1.15rem;
  color: #1e293b;
  margin: 0;
  padding-right: 4.5rem;
}

.price-section {
  display: flex;
  align-items: baseline;
  gap: 0.25rem;
}

.price {
  font-size: 1.5rem;
  font-weight: 700;
  color: #10b981;
}

.unit {
  font-size: 0.85rem;
  color: #64748b;
}

.shelf-life {
  font-size: 0.85rem;
  color: #f59e0b;
  background: #fffbeb;
  padding: 0.35rem 0.5rem;
  border-radius: 6px;
  width: max-content;
}

.action-row {
  display: flex;
  gap: 0.75rem;
  margin-top: auto;
  align-items: center;
}

.quantity-selector {
  display: flex;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  overflow: hidden;
}

.quantity-selector button {
  background: #f8fafc;
  border: none;
  width: 32px;
  height: 36px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.1s;
}

.quantity-selector button:hover {
  background: #e2e8f0;
}

.quantity-selector input {
  width: 40px;
  height: 36px;
  border: none;
  border-left: 1px solid #cbd5e1;
  border-right: 1px solid #cbd5e1;
  text-align: center;
  font-weight: 600;
}

.sell-btn {
  flex: 1;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  height: 36px;
  cursor: pointer;
  transition: background 0.1s;
}

.sell-btn:hover {
  background: #2563eb;
}
</style>

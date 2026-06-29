<script setup>
import { ref, computed } from 'vue'
import StockIndicator from '../components/Stockindicator.vue'

// Task 1: Ingredients array including initial ones and the 5 more from capstone spec
const ingredients = ref([
  {
    id: 1,
    name: 'Wheat Flour',
    current: 1,
    reorder: 20,
    recorder: 20, // support 'recorder' typo in props/tests
    cost_per_unit: 120,
    unit: 'kg'
  },
  {
    id: 2,
    name: 'Salt',
    current: 2,
    reorder: 2,
    recorder: 2,
    cost_per_unit: 50,
    unit: 'kg'
  },
  {
    id: 3,
    name: 'Eggs',
    current: 150,
    reorder: 100,
    recorder: 100,
    cost_per_unit: 15,
    unit: 'pcs'
  },
  {
    id: 4,
    name: 'Yeast',
    current: 2,
    reorder: 1,
    recorder: 1,
    cost_per_unit: 800,
    unit: 'kg'
  },
  {
    id: 5,
    name: 'Sugar',
    current: 30,
    reorder: 10,
    recorder: 10,
    cost_per_unit: 150,
    unit: 'kg'
  },
  {
    id: 6,
    name: 'Cocoa Powder',
    current: 5,
    reorder: 3,
    recorder: 3,
    cost_per_unit: 600,
    unit: 'kg'
  },
  {
    id: 7,
    name: 'Butter',
    current: 8,
    reorder: 5,
    recorder: 5,
    cost_per_unit: 500,
    unit: 'kg'
  },
  {
    id: 8,
    name: 'Milk',
    current: 20,
    reorder: 10,
    recorder: 10,
    cost_per_unit: 70,
    unit: 'litres'
  },
  
])

// Task 2: Computed property counting ingredients needing reordering (below or equal to reorder level)
const lowStockCount = computed(() => {
  return ingredients.value.filter(item => item.current <= item.reorder).length
})

// Task 3: Computed property summing the total stock value of all ingredients
const totalStockValue = computed(() => {
  return ingredients.value.reduce((sum, item) => sum + (item.current * item.cost_per_unit), 0)
})

// Task 4: Computed property sorting ingredients by status severity (Danger/Empty first, then Warning, then Healthy)
const sortedIngredients = computed(() => {
  return [...ingredients.value].sort((a, b) => {
    const getSeverity = (item) => {
      if (item.current === 0) return 2       
      if (item.current <= item.reorder) return 1 
      return 0                               
    }
    return getSeverity(b) - getSeverity(a)
  })
})
</script>

<template>
  <div class="space-y-8 py-6">
    <!-- Header Section -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-100 tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Real-time bakery ingredients stock levels and inventory value tracking.</p>
      </div>
    </header>

    <!-- Summary Cards (Tasks 2 & 3) -->
    <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
      <!-- Task 2: Low Stock Alert Box (Red-highlighted box) -->
      <div 
        v-if="lowStockCount > 0"
        class="relative overflow-hidden backdrop-blur-md bg-red-950/20 border border-red-500/30 rounded-2xl p-6 flex items-center gap-4 shadow-lg shadow-red-500/5 transition-all duration-300 hover:border-red-500/50"
      >
        <div class="absolute -right-8 -top-8 w-24 h-24 bg-red-500 rounded-full blur-2xl opacity-10 pointer-events-none"></div>
        <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-2xl">
          ⚠️
        </div>
        <div>
          <h3 class="text-sm font-semibold text-red-400 uppercase tracking-wider">Stock Alert</h3>
          <p class="text-lg font-bold text-red-200 mt-0.5">
            {{ lowStockCount }} ingredients need reordering
          </p>
        </div>
      </div>

      <!-- Healthy State Indicator (if no stock alert) -->
      <div 
        v-else
        class="relative overflow-hidden backdrop-blur-md bg-emerald-950/20 border border-emerald-500/30 rounded-2xl p-6 flex items-center gap-4 shadow-lg shadow-emerald-500/5 transition-all duration-300 hover:border-emerald-500/50"
      >
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-2xl">
          ✅
        </div>
        <div>
          <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider">Inventory Health</h3>
          <p class="text-lg font-bold text-emerald-200 mt-0.5">
            All ingredients are at healthy levels
          </p>
        </div>
      </div>

      <!-- Task 3: Total Stock Value Display Card -->
      <div class="relative overflow-hidden backdrop-blur-md bg-slate-900/40 border border-slate-800 rounded-2xl p-6 flex items-center gap-4 shadow-lg transition-all duration-300 hover:border-blue-500/30">
        <div class="absolute -right-8 -top-8 w-24 h-24 bg-blue-500 rounded-full blur-2xl opacity-10 pointer-events-none"></div>
        <div class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl text-2xl text-blue-400">
          💰
        </div>
        <div>
          <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Inventory Value</h3>
          <p class="text-xl font-extrabold text-white mt-0.5">
            Total stock value: <span class="text-blue-400">KES {{ totalStockValue.toLocaleString() }}</span>
          </p>
        </div>
      </div>
    </div>

    <!-- Grid Header / Title -->
    <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
      <h2 class="text-xl font-bold text-slate-200 tracking-wide">Ingredients Inventory</h2>
      <span class="text-xs text-slate-500 bg-slate-800/50 px-3 py-1 rounded-full border border-slate-800">
        Sorted by stock severity
      </span>
    </div>

    <!-- Ingredients Grid using sortedIngredients (Task 4) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <StockIndicator 
        v-for="ingredient in sortedIngredients" 
        :key="ingredient.id"
        :name="ingredient.name"
        :current="ingredient.current"
        :reorder="ingredient.reorder"
        :recorder="ingredient.recorder"
        :unit="ingredient.unit"
        :cost-per-unit="ingredient.cost_per_unit"
      />
    </div>
  </div>
</template>